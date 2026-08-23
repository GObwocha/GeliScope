<script setup>
import { ref, onMounted, computed, watch } from 'vue'
import axios from 'axios'
import { useAuthStore } from '@/stores/auth'

const authStore = useAuthStore()
const activeRole = computed(() => authStore.activeRole || 'student')

const teacherStudents = ref([])
const teacherClasses = ref([])
const selectedClass = ref(null)
const selectedStudent = ref(null)
const profileData = ref(null)
const loading = ref(false)

onMounted(async () => {
  if (activeRole.value !== 'teacher') return
  await fetchTeacherClasses()
})

const fetchTeacherClasses = async () => {
  const teacherId = authStore.currentUser?.teacher?.id
  if (!teacherId) return
  try {
    const res = await axios.get(`/api/v1/analysis/teacher/${teacherId}/classes`)
    teacherClasses.value = res.data.data || res.data
  } catch (err) {
    console.error('Failed to fetch teacher classes', err)
  }
}

const fetchTeacherStudents = async () => {
  const teacherId = authStore.currentUser?.teacher?.id
  if (!teacherId || !selectedClass.value) return
  try {
    const res = await axios.get(`/api/v1/analysis/teacher/${teacherId}/students?class_id=${selectedClass.value}`)
    teacherStudents.value = res.data.map(s => ({
      id: s.id,
      name: `${s.user.first_name} ${s.user.last_name} (${s.admission_number})`,
      avatar: s.user.first_name.charAt(0)
    }))
  } catch (err) {
    console.error('Failed to fetch teacher students', err)
  }
}

watch(selectedClass, async () => {
  selectedStudent.value = null
  teacherStudents.value = []
  if (selectedClass.value) {
    await fetchTeacherStudents()
  }
})

const fetchStudentProfile = async () => {
  if (!selectedStudent.value) {
    profileData.value = null
    return
  }

  const teacherId = authStore.currentUser?.teacher?.id
  if (!teacherId) return
  try {
    loading.value = true
    const res = await axios.get(`/api/v1/analysis/teacher/${teacherId}/student/${selectedStudent.value}/comprehensive-profile`)
    profileData.value = res.data
  } catch (err) {
    console.error('Failed to fetch comprehensive profile', err)
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <v-container fluid class="bg-background min-vh-100 pa-6 font-family-sans">
    <!-- Header & Student Selection -->
    <v-row class="mb-6">
      <v-col cols="12">
        <v-card class="pa-5 border d-flex flex-wrap align-center justify-space-between gap-4">
          <div>
            <h1 class="text-h4 font-weight-black lh-tight mb-1 d-flex align-center gap-3">
              <v-icon color="primary" size="36">mdi-account-school</v-icon>
              Student Profile (Tutor View)
            </h1>
            <div class="text-caption text-medium-emphasis">Select a student you teach to view their comprehensive diagnostic profile.</div>
          </div>
          <div class="d-flex gap-4" style="min-width: 400px; flex-wrap: wrap;">
            <v-select
              v-model="selectedClass"
              :items="teacherClasses"
              item-title="name"
              item-value="id"
              label="Select Class"
              variant="outlined"
              color="primary"
              hide-details
              clearable
              style="flex: 1; min-width: 150px;"
            ></v-select>
            
            <v-autocomplete
              v-model="selectedStudent"
              :items="teacherStudents"
              item-title="name"
              item-value="id"
              label="Search Student"
              variant="outlined"
              color="primary"
              hide-details
              clearable
              prepend-inner-icon="mdi-magnify"
              @update:modelValue="fetchStudentProfile"
              :disabled="!selectedClass"
              style="flex: 1; min-width: 200px;"
            ></v-autocomplete>
          </div>
        </v-card>
      </v-col>
    </v-row>

    <!-- Loading State -->
    <div v-if="loading" class="d-flex flex-column align-center justify-center pa-12 mt-12">
      <v-progress-circular indeterminate color="primary" size="64" class="mb-4"></v-progress-circular>
      <h3 class="text-h6 text-medium-emphasis">Fetching AI Diagnostic Profile...</h3>
    </div>

    <!-- Empty State -->
    <div v-else-if="!profileData" class="d-flex flex-column align-center justify-center pa-12 mt-12 text-center">
      <v-icon size="80" color="muted" class="mb-6 opacity-50">mdi-account-search-outline</v-icon>
      <h2 class="text-h5 font-weight-bold text-medium-emphasis mb-2">No Student Selected</h2>
      <p class="text-body-1 text-muted">Use the search bar above to load a student's profile and performance history.</p>
    </div>

    <!-- Profile & Performance Content -->
    <template v-else>
      <!-- Basic Details Card -->
      <v-row class="mb-6">
        <v-col cols="12">
          <v-card class=" border overflow-hidden">
            <div class="bg-primary text-white pa-6 d-flex align-center gap-6 position-relative overflow-hidden">
              <div class="position-absolute" style="top: -50px; right: -50px; opacity: 0.1;">
                <v-icon size="200">mdi-account-circle</v-icon>
              </div>
              <v-avatar size="80" color="white" class="elevation-4">
                <span class="text-h4 font-weight-bold text-primary">
                  {{ profileData.profile.first_name[0] }}{{ profileData.profile.last_name[0] }}
                </span>
              </v-avatar>
              <div style="z-index: 1;">
                <h2 class="text-h3 font-weight-bold mb-1">
                  {{ profileData.profile.first_name }} {{ profileData.profile.last_name }}
                </h2>
                <div class="d-flex flex-wrap gap-4 text-body-1 font-weight-medium" style="opacity: 0.9;">
                  <span class="d-flex align-center gap-1"><v-icon size="small">mdi-email-outline</v-icon> {{ profileData.profile.email }}</span>
                  <span class="d-flex align-center gap-1"><v-icon size="small">mdi-identifier</v-icon> Adm No: {{ profileData.profile.admission_number }}</span>
                  <span class="d-flex align-center gap-1"><v-icon size="small">mdi-google-classroom</v-icon> Class: {{ profileData.profile.class_name }}</span>
                </div>
              </div>
            </div>
          </v-card>
        </v-col>
      </v-row>

      <!-- Exam Performance Breakdown -->
      <v-row>
        <v-col cols="12">
          <h3 class="text-h5 font-weight-bold mb-4 d-flex align-center gap-2">
            <v-icon color="secondary">mdi-text-box-search-outline</v-icon>
            AI Diagnostic Exam Breakdown
          </h3>
          
          <div v-if="profileData.performance.length === 0" class="text-body-1 text-medium-emphasis pa-4 border rounded-xl bg-surface">
            No exams found for this student.
          </div>

          <v-row v-else>
            <v-col cols="12" md="6" v-for="(exam, idx) in profileData.performance" :key="idx">
              <v-card class=" border h-100 d-flex flex-column">
                <div class="pa-5 border-bottom d-flex justify-space-between align-center">
                  <div>
                    <h4 class="text-h6 font-weight-bold mb-1">{{ exam.exam_name }}</h4>
                    <div class="text-caption font-weight-medium text-medium-emphasis d-flex gap-3">
                      <span><v-icon size="small" class="mr-1">mdi-book-open-variant</v-icon>{{ exam.subject }}</span>
                      <span><v-icon size="small" class="mr-1">mdi-calendar-range</v-icon>{{ exam.round }}</span>
                    </div>
                  </div>
                  <div class="d-flex flex-column align-end">
                    <span class="text-h4 font-weight-black" :class="exam.percentage >= 50 ? 'text-success' : 'text-error'">
                      {{ exam.percentage }}%
                    </span>
                    <span class="text-caption text-medium-emphasis">{{ exam.score }} / {{ exam.max_score }}</span>
                  </div>
                </div>

                <div class="pa-5 flex-grow-1 bg-background">
                  <h5 class="text-subtitle-1 font-weight-bold text-error mb-3 d-flex align-center gap-2">
                    <v-icon color="error" size="small">mdi-alert-circle-outline</v-icon> Top Failure Points
                  </h5>
                  
                  <div v-if="exam.failure_points && exam.failure_points.length > 0">
                    <ul class="pl-4">
                      <li v-for="(point, pIdx) in exam.failure_points" :key="pIdx" class="text-body-2 mb-2 text-high-emphasis">
                        {{ point }}
                      </li>
                    </ul>
                  </div>
                  <div v-else class="text-caption text-medium-emphasis font-italic">
                    No diagnostic failure points found. The AI might not have analyzed this exam paper yet.
                  </div>
                </div>
                <div class="pa-3 text-right text-caption text-medium-emphasis border-top">
                  Graded: {{ new Date(exam.date).toLocaleDateString() }}
                </div>
              </v-card>
            </v-col>
          </v-row>
        </v-col>
      </v-row>
    </template>
  </v-container>
</template>

<style scoped>
.font-family-sans {
  font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}
.lh-tight {
  line-height: 1.1 !important;
}
.gap-1 { gap: 4px; }
.gap-2 { gap: 8px; }
.gap-3 { gap: 12px; }
.gap-4 { gap: 16px; }
.gap-6 { gap: 24px; }
.border-bottom {
  border-bottom: 1px solid rgba(var(--v-border-color), var(--v-border-opacity));
}
.border-top {
  border-top: 1px solid rgba(var(--v-border-color), var(--v-border-opacity));
}
.opacity-50 {
  opacity: 0.5;
}
.text-muted {
  color: rgba(var(--v-theme-on-surface), 0.6);
}
</style>

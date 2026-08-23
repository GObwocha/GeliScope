<script setup>
import { ref, onMounted, computed } from 'vue'
import { useAuthStore } from '@/stores/auth'
import axios from 'axios'
import { useRouter } from 'vue-router'

const authStore = useAuthStore()
const router = useRouter()

const loadingClasses = ref(true)
const generating = ref(false)
const classes = ref([])
const selectedClass = ref(null)
const selectedExamId = ref(null)

const diagnosisData = ref(null)

const currentUser = computed(() => authStore.currentUser)

onMounted(async () => {
  if (!authStore.isAuthenticated || currentUser.value?.role !== 'teacher') {
    router.push('/dashboard')
    return
  }
  
  try {
    const response = await axios.get(`/api/v1/analysis/teacher/${currentUser.value.teacher.id}/classes`, {
      headers: { Authorization: `Bearer ${authStore.token}` }
    })
    classes.value = response.data
  } catch (error) {
    console.error('Failed to fetch classes:', error)
  } finally {
    loadingClasses.value = false
  }
})

const examsForSelectedClass = computed(() => {
  if (!selectedClass.value) return []
  return selectedClass.value.exams || []
})

const generateDiagnosis = async () => {
  if (!selectedClass.value || !selectedExamId.value) return
  
  generating.value = true
  diagnosisData.value = null
  
  try {
    const response = await axios.post('/api/v1/analysis/class/generate-diagnosis', {
      teacher_id: currentUser.value.id,
      class_id: selectedClass.value.id,
      subject_id: selectedClass.value.subject_id,
      exam_id: selectedExamId.value
    }, {
      headers: { Authorization: `Bearer ${authStore.token}` }
    })
    
    diagnosisData.value = response.data
  } catch (error) {
    console.error('Failed to generate diagnosis:', error)
    if (error.response?.data?.message) {
      alert(error.response.data.message)
    } else {
      alert('Failed to generate diagnosis. Ensure student analysis data exists for the selected exam.')
    }
  } finally {
    generating.value = false
  }
}
</script>

<template>
  <v-container>
    <v-btn variant="text" prepend-icon="mdi-arrow-left" to="/dashboard" class="mb-4">Back to Dashboard</v-btn>
    
    <v-card class="pa-6 rounded-3xl border bg-surface mb-8" elevation="4">
      <h2 class="text-h4 font-weight-black d-flex align-center mb-4">
        <v-icon color="secondary" size="36" class="mr-3">mdi-google-circles-extended</v-icon>
        Class Diagnosis (AI)
      </h2>
      <p class="text-subtitle-1 text-medium-emphasis mb-6">
        Select a class and an exam to generate an AI-powered diagnostic overview of the class's performance across Bloom's Taxonomy levels.
      </p>
      
      <v-row>
        <v-col cols="12" md="5">
          <v-select
            v-model="selectedClass"
            :items="classes"
            item-title="name"
            return-object
            label="Select Class"
            variant="outlined"
            bg-color="background"
            :loading="loadingClasses"
            @update:modelValue="selectedExamId = null"
          >
            <template v-slot:item="{ props, item }">
              <v-list-item v-bind="props" :subtitle="item.subject_name"></v-list-item>
            </template>
            <template v-slot:selection="{ item }">
              {{ item.name }} ({{ item.subject_name }})
            </template>
          </v-select>
        </v-col>
        
        <v-col cols="12" md="5">
          <v-select
            v-model="selectedExamId"
            :items="examsForSelectedClass"
            item-title="name"
            item-value="id"
            label="Select Exam"
            variant="outlined"
            bg-color="background"
            :disabled="!selectedClass"
          ></v-select>
        </v-col>
        
        <v-col cols="12" md="2" class="d-flex align-center">
          <v-btn 
            color="primary" 
            block 
            size="large" 
            class="rounded-xl font-weight-bold" 
            :loading="generating" 
            :disabled="!selectedClass || !selectedExamId"
            @click="generateDiagnosis"
          >
            Generate
          </v-btn>
        </v-col>
      </v-row>
    </v-card>
    
    <v-expand-transition>
      <div v-if="diagnosisData">
        <v-card class="pa-6 rounded-3xl border bg-surface mb-6" elevation="6">
          <div class="d-flex align-center justify-space-between mb-6">
            <h3 class="text-h5 font-weight-black d-flex align-center">
              <v-icon color="primary" class="mr-2">mdi-chart-pie</v-icon> Class Overview
            </h3>
            <v-chip color="success" size="small" variant="flat" class="font-weight-bold">
              Avg: {{ diagnosisData.class_overview?.class_average_percentage || 'N/A' }}%
            </v-chip>
          </div>
          
          <v-row class="mb-2">
            <v-col cols="12" sm="6">
              <div class="pa-4 rounded-2xl bg-surface-light border text-center h-100">
                <div class="text-caption text-medium-emphasis font-weight-bold uppercase mb-2">Highest Performing Area</div>
                <div class="text-h6 font-weight-bold text-success">{{ diagnosisData.class_overview?.highest_performing_area || 'N/A' }}</div>
              </div>
            </v-col>
            <v-col cols="12" sm="6">
              <div class="pa-4 rounded-2xl bg-surface-light border text-center h-100">
                <div class="text-caption text-medium-emphasis font-weight-bold uppercase mb-2">Lowest Performing Area</div>
                <div class="text-h6 font-weight-bold text-error">{{ diagnosisData.class_overview?.lowest_performing_area || 'N/A' }}</div>
              </div>
            </v-col>
          </v-row>
        </v-card>
        
        <v-card class="pa-6 rounded-3xl border bg-surface" elevation="6">
          <h3 class="text-h5 font-weight-black d-flex align-center mb-6">
            <v-icon color="secondary" class="mr-2">mdi-lightbulb-on-outline</v-icon> Actionable Suggestions
          </h3>
          
          <v-list bg-color="transparent" class="pa-0">
            <v-list-item
              v-for="(suggestion, idx) in diagnosisData.suggestions"
              :key="idx"
              class="px-0 mb-3 rounded-xl border bg-surface-light"
            >
              <template v-slot:prepend>
                <v-avatar color="secondary" variant="tonal" size="36" class="ml-2 mr-3 font-weight-bold">
                  {{ idx + 1 }}
                </v-avatar>
              </template>
              <v-list-item-title class="font-weight-bold text-body-1" style="white-space: normal;">{{ suggestion }}</v-list-item-title>
            </v-list-item>
          </v-list>
        </v-card>
      </div>
    </v-expand-transition>
  </v-container>
</template>

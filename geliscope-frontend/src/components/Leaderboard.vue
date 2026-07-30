<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import { useAuthStore } from '@/stores/auth'

const authStore = useAuthStore()

const loading = ref(true)
const modalLoading = ref(false)
const isModalOpen = ref(false)

const exams = ref([])
const selectedExam = ref(null)
const leaderboardData = ref([])

const selectedStudent = ref(null)

// --- LOGIC --- //

const fetchLeaderboard = async () => {
  if (!selectedExam.value) return
  
  loading.value = true
  try {
    const studentId = authStore.currentUser?.student?.id || ''
    const response = await axios.get(`/api/v1/leaderboard`, {
      params: {
        exam_id: selectedExam.value,
        student_id: studentId
      }
    })
    leaderboardData.value = response.data
  } catch(e) {
    console.error('Failed to fetch leaderboard:', e)
  } finally {
    loading.value = false
  }
}

const openStudentProfile = async (student) => {
  selectedStudent.value = { ...student } // Set basic info immediately
  isModalOpen.value = true
  modalLoading.value = true
  
  try {
    // Note: To make the modal work fully with real data, we would need a new endpoint 
    // for student summaries. For now, we will leave the modal chart blank but show the basic info.
    selectedStudent.value.marksHistory = []
    selectedStudent.value.strengths = []
  } catch (e) {
    console.error(e)
  } finally {
    modalLoading.value = false
  }
}

const closeModal = () => {
  isModalOpen.value = false
}

// --- STYLING HELPERS --- //
const getRankColor = (rank) => {
  if (rank === 1) return 'text-warning' // Gold
  if (rank === 2) return 'text-grey-darken-1' // Silver
  if (rank === 3) return 'text-brown-darken-2' // Bronze
  return 'text-high-emphasis'
}

const getScoreColor = (score) => {
  if (score >= 90) return 'success'
  if (score >= 80) return 'primary'
  if (score >= 70) return 'warning'
  return 'error'
}

// Chart Logic for Modal
const chartMetrics = computed(() => {
  if (!selectedStudent.value || !selectedStudent.value.marksHistory) return { points: [], polylineStr: '', width: 0, height: 0 }
  
  const history = selectedStudent.value.marksHistory
  const scores = history.map(e => e.score)
  const maxScore = Math.max(...scores)
  const minScore = Math.min(...scores)
  
  const width = 400
  const height = 160
  const padLeft = 20
  const padRight = 20
  const padTop = 20
  const padBottom = 25
  
  const plotWidth = width - padLeft - padRight
  const plotHeight = height - padTop - padBottom
  
  const points = history.map((e, idx) => {
    const x = padLeft + (idx / (history.length - 1)) * plotWidth
    const y = height - padBottom - (e.score / 100) * plotHeight
    return { ...e, x, y, isMax: e.score === maxScore, isMin: e.score === minScore }
  })
  
  const polylineStr = points.map(p => `${p.x},${p.y}`).join(' ')
  
  return { points, maxScore, minScore, polylineStr, width, height }
})

const getBarColor = (score) => {
  if (score === chartMetrics.value.maxScore) return 'bg-success text-success'
  if (score === chartMetrics.value.minScore) return 'bg-error text-error'
  return 'bg-high-emphasis text-high-emphasis'
}

onMounted(async () => {
  try {
    const response = await axios.get('/api/v1/exams')
    if (response.data && response.data.data) {
      exams.value = [
        { id: 'all', name: 'All Exams (Overall Average)' },
        ...response.data.data
      ]
      if (exams.value.length > 0) {
        selectedExam.value = exams.value[0].id
        fetchLeaderboard()
      }
    }
  } catch (err) {
    console.error('Failed to load exams', err)
    loading.value = false
  }
})
</script>

<template>
  <v-container fluid class="bg-background min-vh-100 pa-6 font-family-sans">
    <!-- Header Section -->
    <v-row class="mb-4">
       <v-col cols="12" class="d-flex justify-space-between align-center">
         <div>
           <h1 class="text-h3 font-weight-medium lh-tight mb-2">National Leaderboard</h1>
           <div class="text-caption text-medium-emphasis">Top performing students across standard exams.</div>
         </div>
         <div class="extracted-style-1" >
           <v-select
             v-model="selectedExam"
             :items="exams"
             item-title="name"
             item-value="id"
             variant="outlined"
             density="compact"
             bg-color="surface"
             hide-details
             @update:modelValue="fetchLeaderboard"
           >
             <template v-slot:prepend-inner>
               <v-icon size="small" class="mr-2 text-medium-emphasis">mdi-file-document-edit-outline</v-icon>
             </template>
           </v-select>
         </div>
       </v-col>
    </v-row>

    <!-- Loading State -->
    <v-row v-if="loading">
      <v-col class="d-flex justify-center my-12">
        <v-progress-circular indeterminate color="primary" size="64"></v-progress-circular>
      </v-col>
    </v-row>

    <!-- Table Section -->
    <v-row v-else>
      <v-col cols="12">
        <v-card class="rounded-xl bg-surface elevation-2 border overflow-hidden">
           <v-table class="bg-transparent" hover>
             <thead>
               <tr>
                 <th class="text-left font-weight-bold text-caption text-medium-emphasis text-uppercase px-6 py-4">Rank</th>
                 <th class="text-left font-weight-bold text-caption text-medium-emphasis text-uppercase py-4">Student Name</th>
                 <th class="text-left font-weight-bold text-caption text-medium-emphasis text-uppercase py-4">Average Score</th>
                 <th class="text-left font-weight-bold text-caption text-medium-emphasis text-uppercase py-4">School</th>
                 <th class="text-left font-weight-bold text-caption text-medium-emphasis text-uppercase py-4">County</th>
                 <th class="text-right font-weight-bold text-caption text-medium-emphasis text-uppercase px-6 py-4">Actions</th>
               </tr>
             </thead>
             <tbody>
               <tr v-for="student in leaderboardData" :key="student.id" class="border-bottom-light align-middle">
                 <td class="px-6 py-4">
                   <div class="d-flex align-center gap-2">
                     <span class="font-weight-bold text-body-1" :class="getRankColor(student.rank)">#{{ student.rank }}</span>
                     <v-icon v-if="student.rank <= 3" size="small" color="warning" :class="getRankColor(student.rank).replace('text-', 'text-')">mdi-trophy</v-icon>
                   </div>
                 </td>
                 <td class="py-4 font-weight-bold text-body-2">{{ student.name }}</td>
                 <td class="py-4">
                   <v-chip size="small" :color="getScoreColor(student.score)" variant="flat" class="font-weight-bold px-3">
                     {{ student.score }}%
                   </v-chip>
                 </td>
                 <td class="py-4 text-body-2 text-medium-emphasis">{{ student.school }}</td>
                 <td class="py-4 text-body-2 text-medium-emphasis">{{ student.county }}</td>
                 <td class="text-right px-6 py-4">
                   <v-btn 
                     size="small" 
                     variant="tonal" 
                     color="primary" 
                     class="font-weight-bold rounded-lg text-none"
                     @click="openStudentProfile(student)"
                   >
                     View Profile
                   </v-btn>
                 </td>
               </tr>
             </tbody>
           </v-table>
        </v-card>
      </v-col>
    </v-row>

    <!-- Student Profile Modal -->
    <v-dialog v-model="isModalOpen" max-width="600px" persistent>
      <v-card class="rounded-xl bg-background border" v-if="selectedStudent">
        <!-- Modal Header -->
        <div class="d-flex justify-space-between align-center pa-6 border-bottom-light bg-surface">
          <div class="d-flex align-center gap-4">
            <v-avatar size="48" color="primary" variant="tonal">
              <span class="font-weight-bold text-h6">{{ selectedStudent.name[0] }}</span>
            </v-avatar>
            <div>
              <div class="text-h6 font-weight-bold lh-tight">{{ selectedStudent.name }}</div>
              <div class="text-caption text-medium-emphasis">{{ selectedStudent.school }} • {{ selectedStudent.county }}</div>
            </div>
          </div>
          <v-btn icon="mdi-close" variant="text" density="compact" @click="closeModal"></v-btn>
        </div>

        <div class="pa-6 eoverflow-y: auto;">
           <div class="text-caption text-medium-emphasis font-weight-bold text-uppercase mb-4">Academic Year Progress</div>
           
           <!-- Graph -->
           <v-card class="bg-surface border elevation-0 rounded-lg pa-4 mb-6">
             <div v-if="modalLoading" class="d-flex justify-center align-center py-8">
               <v-progress-circular indeterminate color="primary" size="32"></v-progress-circular>
             </div>
             <div v-else class="position-relative w-100 d-flex flex-column justify-end pt-2">
                <svg :viewBox="`0 0 ${chartMetrics.width} ${chartMetrics.height}`" class="w-100 overflow-visible eax-height: 160px;">
                 <polyline 
                    :points="chartMetrics.polylineStr" 
                    fill="none" 
                    stroke="currentColor" 
                    stroke-width="3" 
                    stroke-linecap="round" 
                    stroke-linejoin="round" 
                    class="text-primary" 
                  />
                 <g v-for="(p, i) in chartMetrics.points" :key="i">
                   <circle 
                      :cx="p.x" 
                      :cy="p.y" 
                      :r="4" 
                      fill="currentColor" 
                      class="text-surface" 
                      stroke="currentColor" 
                      stroke-width="2.5" 
                      :class="getBarColor(p.score).split(' ')[1]" 
                    />
                    <text 
                      :x="p.x" 
                      :y="p.y - 12" 
                      text-anchor="middle" 
                      font-size="12" 
                      font-weight="bold" 
                      fill="currentColor" 
                      :class="getBarColor(p.score).split(' ')[1]"
                    >
                      {{ p.score }}%
                    </text>
                    <text 
                      :x="p.x" 
                      :y="chartMetrics.height - 5" 
                      text-anchor="middle" 
                      font-size="10" 
                      font-weight="bold" 
                      fill="currentColor" 
                      class="text-medium-emphasis"
                    >
                      {{ p.id }}
                    </text>
                 </g>
                </svg>
             </div>
           </v-card>

           <div class="text-caption text-medium-emphasis font-weight-bold text-uppercase mb-4">Core Competencies</div>
           
           <!-- Strengths Dropdown -->
           <v-expansion-panels variant="accordion" v-if="!modalLoading">
             <v-expansion-panel class="bg-surface border rounded-lg overflow-hidden">
               <v-expansion-panel-title class="font-weight-bold text-success">
                 <div class="d-flex align-center">
                   <v-icon color="success" class="mr-3">mdi-star-circle-outline</v-icon> Academic Strengths
                 </div>
               </v-expansion-panel-title>
               <v-expansion-panel-text class="pt-4 bg-background">
                  <div v-for="(str, i) in selectedStudent.strengths" :key="i" class="mb-4 border-bottom-light pb-4 last:border-none last:pb-0">
                     <div class="text-body-2 font-weight-bold">{{ str.title }}</div>
                     <div class="text-caption text-medium-emphasis">{{ str.detail }}</div>
                  </div>
               </v-expansion-panel-text>
             </v-expansion-panel>
           </v-expansion-panels>
           <div v-else class="d-flex justify-center align-center py-4">
             <v-progress-circular indeterminate color="primary" size="24"></v-progress-circular>
           </div>
        </div>
      </v-card>
    </v-dialog>

  </v-container>
</template>

<style scoped>
.extracted-style-1 {
  width: 300px;
}

.extracted-style-2 {
  max-height: 70vh; overflow-y: auto;
}

.extracted-style-3 {
  height: 180px;
}

.extracted-style-4 {
  max-height: 160px;
}

.lh-tight {
  line-height: 1.1 !important;
}

.gap-1 { gap: 4px; }
.gap-2 { gap: 8px; }
.gap-3 { gap: 12px; }
.gap-4 { gap: 16px; }
.gap-6 { gap: 24px; }

.hover-primary:hover {
  color: rgb(var(--v-theme-primary)) !important;
}

.transition-colors {
  transition: color 0.2s ease;
}

.bg-high-emphasis {
  background-color: rgba(var(--v-theme-on-background), 0.9) !important;
  color: rgb(var(--v-theme-background)) !important;
}

.text-high-emphasis {
  color: rgba(var(--v-theme-on-background), 0.9) !important;
}

.border-bottom-light {
  border-bottom: 1px solid rgba(var(--v-border-color), 0.05);
}

.last\:border-none:last-child {
  border-bottom: none;
}

.last\:pb-0:last-child {
  padding-bottom: 0 !important;
}

.font-family-sans {
  font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}
</style>
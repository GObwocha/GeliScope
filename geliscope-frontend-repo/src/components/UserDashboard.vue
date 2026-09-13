<script setup>
import { ref, onMounted, computed, watch } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useExamStore } from '@/stores/exams'
import axios from 'axios'
import { useRouter } from 'vue-router'

const router = useRouter()
const authStore = useAuthStore()
const examStore = useExamStore()

// Default / active role view switcher ('student' | 'parent' | 'teacher')
const activeRole = computed(() => authStore.activeRole || 'student')
const loading = ref(true)

const mottoFontType = ref(localStorage.getItem('motto_font_type') || 'Inter')
const mottoFontSize = ref(localStorage.getItem('motto_font_size') || '16')
const mottoFontColor = ref(localStorage.getItem('motto_font_color') || '#000000')

const mottoStyles = computed(() => ({
 fontFamily: mottoFontType.value,
 fontSize: Math.min(Number(mottoFontSize.value) || 16, 24) + 'px',
 color: mottoFontColor.value
}))

// User Identity State
const userProfile = computed(() => {
 return authStore.currentUser || {
 first_name: 'Guest',
 last_name: 'User',
 email: '',
 role: 'student',
 school: { name: 'Unknown School', subcounty: null },
 class_group: 'Unknown Class',
 student: { admission_number: '', personal_motto: 'Strive for greatness', skills: [] },
 teacher: null,
 parent: null
 }
})

const suggestedCareers = ref([])
const fetchingCareers = ref(false)

const fetchSuggestedCareers = async () => {
 if (activeRole.value !== 'student' || !userProfile.value.student?.id) return
 // If the student doesn't have any skills, skip
 if (!userProfile.value.student.skills || userProfile.value.student.skills.length === 0) {
 return
 }

 fetchingCareers.value = true
 try {
 const response = await axios.post('/api/v1/analysis/careers/suggest', {
 student_id: userProfile.value.student.id
 }, {
 headers: {
 Authorization: `Bearer ${authStore.token}`
 }
 })
 suggestedCareers.value = response.data.careers || []
 } catch (err) {
 console.error('Failed to fetch career suggestions', err)
 } finally {
 fetchingCareers.value = false
 }
}

// Chronological Trend of Student Average Marks (`exam_results` table sync across all historical terms)
const trendExams = computed(() => examStore.trendExams)
const recentExams = computed(() => examStore.recentExams)

const activeExamPoint = ref(null)

watch(trendExams, (newVal) => {
  if (newVal && newVal.length > 0 && !activeExamPoint.value) {
    activeExamPoint.value = newVal[newVal.length - 1]
  }
}, { immediate: true })

const presentCumulativeAvg = computed(() => {
  if (!trendExams.value || trendExams.value.length === 0) return '0.0';
  const sum = trendExams.value.reduce((acc, curr) => acc + (Number(curr.avg) || 0), 0);
  return (sum / trendExams.value.length).toFixed(1);
})

const overallTrajectoryGain = computed(() => {
  if (!trendExams.value || trendExams.value.length < 2) return '0.0';
  const first = Number(trendExams.value[0].avg) || 0;
  const last = Number(trendExams.value[trendExams.value.length - 1].avg) || 0;
  const diff = last - first;
  return diff > 0 ? `+${diff.toFixed(1)}` : diff.toFixed(1);
})

// SVG Chart Metrics (Computed for high-precision vector rendering without external bundle overhead)
const chartMetrics = computed(() => {
 const width = 800
 const height = 260
 const padTop = 25
 const padBottom = 45
 const padLeft = 55
 const padRight = 35
 const plotWidth = width - padLeft - padRight
 const plotHeight = height - padTop - padBottom
 
 const minVal = 45
 const maxVal = 100
 
  if (!trendExams.value || trendExams.value.length === 0) {
    return { width, height, padTop, padBottom, padLeft, padRight, plotWidth, plotHeight, bottomY: padTop + plotHeight, points: [], linePath: '', areaPath: '', gridLines: [] }
  }

  const points = trendExams.value.map((item, idx) => {
    let x = padLeft;
    if (trendExams.value.length > 1) {
      x = padLeft + (idx / (trendExams.value.length - 1)) * plotWidth
    }
    const normalized = Math.max(0, Math.min(1, (item.avg - minVal) / (maxVal - minVal)))
    const y = (padTop + plotHeight) - (normalized * plotHeight)
    return { ...item, x, y, idx }
  })

 const linePath = points.map(p => `${p.x},${p.y}`).join(' ')
 const bottomY = padTop + plotHeight
 const areaPath = `${points[0].x},${bottomY} ${linePath} ${points[points.length - 1].x},${bottomY}`

 // Grid levels (50%, 60%, 70%, 80%, 90%, 100%)
 const gridLines = [50, 60, 70, 80, 90, 100].map(val => {
 const normalized = (val - minVal) / (maxVal - minVal)
 const y = (padTop + plotHeight) - (normalized * plotHeight)
 return { val: `${val}%`, y }
 })

 return { width, height, padTop, padBottom, padLeft, padRight, plotWidth, plotHeight, bottomY, points, linePath, areaPath, gridLines }
})

// (Removed old recentExams ref, now using computed from examStore)

// MongoDB Unstructured AI Diagnostics (`exam_analyses` & `student_cognitive_profiles`)
const fetchingCognitiveProfile = ref(false)
const bloomsMastery = ref({
 remembering: 0,
 understanding: 0,
 applying: 0,
 analyzing: 0,
 evaluating: 0,
 last_analyzed: 'Never'
})

const fetchCognitiveProfile = async () => {
 if (activeRole.value !== 'student' || !userProfile.value.student?.id) return

 fetchingCognitiveProfile.value = true
 try {
   const response = await axios.get(`/api/v1/analysis/cognitive-profile/${userProfile.value.student.id}`, {
     headers: { Authorization: `Bearer ${authStore.token}` }
   })
   if (response.data && response.data.average_blooms_taxonomy
) {
     const avgs = response.data.average_blooms_taxonomy

     bloomsMastery.value = {
       remembering: avgs.remembering || 0,
       understanding: avgs.understanding || 0,
       applying: avgs.applying || 0,
       analyzing: avgs.analyzing || 0,
       evaluating: avgs.evaluating || 0,
       last_analyzed: new Date(response.data.last_updated).toLocaleDateString()
     }
   }
 } catch (err) {
   console.error('Failed to fetch cognitive profile', err)
 } finally {
   fetchingCognitiveProfile.value = false
 }
}

const getProgressColor = (score) => {
  if (score > 80) return 'success'
  if (score > 60) return 'primary'
  if (score > 40) return 'warning'
  return 'error'
}

import StudentTodoService from '@/services/StudentTodoService'
import StudentProfileP from './StudentProfileP.vue'

const todos = ref([])
const loadingTodos = ref(false)

const parentStudents = computed(() => {
  if (activeRole.value !== 'parent') return [];
  return authStore.currentUser?.parent_profile?.students || [];
})
const selectedParentStudent = ref(null)

watch(selectedParentStudent, async (newVal) => {
  if (newVal) {
    await loadTodos()
  }
})

const loadTodos = async () => {
  let studentId = null;
  if (activeRole.value === 'student') {
    studentId = authStore.currentUser?.student?.id;
  } else if (activeRole.value === 'parent') {
    studentId = selectedParentStudent.value;
  }

  if (!studentId) return
  try {
    loadingTodos.value = true
    todos.value = await StudentTodoService.getTodos(studentId)
  } catch (err) {
    console.error('Failed to load todos', err)
  } finally {
    loadingTodos.value = false
  }
}

const toggleTodo = async (todo) => {
  try {
    todo.is_completed = !todo.is_completed
    await StudentTodoService.updateTodo(todo.id, { is_completed: todo.is_completed })
  } catch (err) {
    console.error('Failed to update todo', err)
    todo.is_completed = !todo.is_completed // revert
  }
}

const deleteTodo = async (todoId) => {
  try {
    await StudentTodoService.deleteTodo(todoId)
    todos.value = todos.value.filter(t => t.id !== todoId)
  } catch (err) {
    console.error('Failed to delete todo', err)
  }
}

const teacherStreamSummary = ref({
 class_name: 'Loading...',
 total_students: 0,
 stream_average: '0%',
 top_error: 'Loading...',
 pending_scans: 0
})

const latestExamMean = ref(null)
const aiReportDialog = ref(false)

const analysisDialog = ref(false)
const fetchingAnalysis = ref(false)
const analysisResult = ref(null)

const openAnalysisDialog = async () => {
  if (!activeExamPoint.value) return
  analysisResult.value = null
  analysisDialog.value = true
  fetchingAnalysis.value = true
  
  try {
    const studentId = userProfile.value.student.id
    const examId = activeExamPoint.value.id
    const response = await axios.get(`/api/v1/analysis/exam/${studentId}/${examId}`, {
      headers: { Authorization: `Bearer ${authStore.token}` }
    })
    analysisResult.value = response.data
  } catch (err) {
    console.error('Failed to fetch analysis', err)
    if (err.response && err.response.status === 404) {
      analysisResult.value = { error: 'No AI Analysis exists yet for this exam.' }
    } else {
      analysisResult.value = { error: 'Failed to fetch analysis.' }
    }
  } finally {
    fetchingAnalysis.value = false
  }
}

const fetchingAiReport = ref(false)
const aiReport = ref(null)

const fetchTeacherDashboard = async () => {
  if (activeRole.value !== 'teacher' || !userProfile.value.teacher?.id) return
  
  try {
    const response = await axios.get(`/api/v1/analysis/teacher/${userProfile.value.teacher.id}/dashboard`, {
      headers: { Authorization: `Bearer ${authStore.token}` }
    })
    
    teacherStreamSummary.value = {
      class_name: 'All Assigned Classes',
      total_students: '-',
      stream_average: response.data.class_mean + '%',
      top_error: response.data.stream_bottleneck,
      pending_scans: 0
    }
    
    latestExamMean.value = {
      mean: response.data.class_mean,
      exam_name: response.data.exam_name,
      date_recorded: response.data.date_recorded,
      analysis_class_id: response.data.analysis_class_id,
      analysis_exam_id: response.data.analysis_exam_id
    }
  } catch (error) {
    console.error('Failed to fetch teacher dashboard', error)
  }
}

const openAiReportDialog = async () => {
  aiReportDialog.value = true
  aiReport.value = null
  fetchingAiReport.value = true
  
  try {
    if (latestExamMean.value && latestExamMean.value.analysis_class_id && latestExamMean.value.analysis_exam_id) {
      const response = await axios.get(`/api/v1/analysis/class/${latestExamMean.value.analysis_class_id}/${latestExamMean.value.analysis_exam_id}`, {
        headers: { Authorization: `Bearer ${authStore.token}` }
      })
      aiReport.value = response.data
    }
  } catch (err) {
    console.error('Failed to fetch AI report', err)
  } finally {
    fetchingAiReport.value = false
  }
}

watch(activeRole, async (newRole) => {
  if (newRole === 'parent' && parentStudents.value.length > 0 && !selectedParentStudent.value) {
    selectedParentStudent.value = parentStudents.value[0].id;
  }
  if (newRole === 'student' || newRole === 'parent') {
    await loadTodos()
  }
})

  const manageChildrenDialog = ref(false)
  const searchQuery = ref('')
  const searchResults = ref([])
  const isSearching = ref(false)
  const isLinking = ref(false)
  const isUnlinking = ref(false)

  const searchStudents = async () => {
    if (!searchQuery.value) return
    isSearching.value = true
    try {
      const res = await axios.get(`/api/v1/parents/students/search?q=${searchQuery.value}`, {
        headers: { Authorization: `Bearer ${authStore.token}` }
      })
      searchResults.value = res.data
    } catch (err) {
      console.error(err)
    } finally {
      isSearching.value = false
    }
  }

  const linkStudent = async (studentId) => {
    const parentId = authStore.currentUser?.parent_profile?.id
    if (!parentId) return
    
    isLinking.value = true
    try {
      await axios.post(`/api/v1/parents/${parentId}/students`, { student_id: studentId }, {
        headers: { Authorization: `Bearer ${authStore.token}` }
      })
      searchQuery.value = ''
      searchResults.value = []
      await authStore.fetchUser(authStore.token) // Refresh parent students
      if (parentStudents.value.length === 1) {
          selectedParentStudent.value = parentStudents.value[0].id
      }
    } catch (err) {
      console.error(err)
    } finally {
      isLinking.value = false
    }
  }

  const unlinkStudent = async (studentId) => {
    const parentId = authStore.currentUser?.parent_profile?.id
    if (!parentId) return
    if (!confirm('Are you sure you want to unlink this student?')) return
    
    isUnlinking.value = true
    try {
      await axios.delete(`/api/v1/parents/${parentId}/students/${studentId}`, {
        headers: { Authorization: `Bearer ${authStore.token}` }
      })
      if (selectedParentStudent.value === studentId) {
        selectedParentStudent.value = null
      }
      await authStore.fetchUser(authStore.token)
      if (parentStudents.value.length > 0 && !selectedParentStudent.value) {
          selectedParentStudent.value = parentStudents.value[0].id
      }
    } catch (err) {
      console.error(err)
    } finally {
      isUnlinking.value = false
    }
  }

// Load active session from local storage or backend API
onMounted(() => {
  if (!authStore.isAuthenticated) {
    router.push('/login')
  } else {
    loading.value = false
    fetchSuggestedCareers()
    fetchCognitiveProfile()
    fetchTeacherDashboard()
    if (userProfile.value.student?.id) {
      examStore.fetchStudentExams(userProfile.value.student.id)
    }

    if (activeRole.value === 'parent' && parentStudents.value.length > 0) {
      selectedParentStudent.value = parentStudents.value[0].id;
    }
    if (activeRole.value === 'student' || activeRole.value === 'parent') {
      loadTodos()
    }
  }
})
</script>


<template>
 <v-container >
 <!-- Top Welcome Banner & Role Switcher -->
 <v-card class="pa-6 rounded-3xl border bg-surface  mb-8" elevation="4">
 <v-row align="center" justify="space-between">
 <v-col cols="12" md="7" class="d-flex align-center">
 <v-avatar :color="activeRole === 'student' ? 'primary' : (activeRole === 'teacher' ? 'secondary' : 'accent')" size="76" class="mr-6 font-weight-black text-h4  flex-shrink-0">
 <span class="">{{ userProfile.first_name[0] }}{{ userProfile.last_name[0] }}</span>
 </v-avatar>
 <div>
 <div class="d-flex align-center gap-2 mb-2">
 <v-chip :color="activeRole === 'student' ? 'primary' : (activeRole === 'teacher' ? 'secondary' : 'accent')" size="small" variant="flat" class="font-weight-black uppercase">
 {{ activeRole }} DASHBOARD
 </v-chip>
 <v-chip :color="activeRole === 'student' ? 'primary' : (activeRole === 'teacher' ? 'secondary' : 'accent')" size="small" variant="outlined" class="font-weight-bold ma-5" v-if="activeRole === 'teacher' && userProfile.teacher">
 {{ userProfile.teacher?.employee_number }}
 </v-chip>
 <v-chip :color="activeRole === 'student' ? 'primary' : (activeRole === 'teacher' ? 'secondary' : 'accent')" size="small" variant="outlined" class="font-weight-bold ma-5" v-if="activeRole === 'student' && userProfile.student">
 {{ userProfile.student?.admission_number }}
 </v-chip>
 </div>
 <h1 class="text-h3 font-weight-black">Welcome back, {{ userProfile.first_name }}!</h1>
 <p class="text-subtitle-1 text-medium-emphasis mb-0 d-flex align-center mt-1">
 <v-icon size="18" color="primary" class="mr-1">mdi-school-outline</v-icon>
 {{ userProfile.student?.school?.name || userProfile.teacher?.school?.name || 'GeliScope Academy' }} &bull; {{ activeRole === 'teacher' ? 'TSC Examiner' : userProfile.class_group }}
 </p>
 </div>
 </v-col>

 <v-col cols="12" md="4" class="text-md-right d-flex align-center justify-md-end" v-if="activeRole === 'student' && userProfile.student?.personal_motto">
 <div class="motto-display font-italic px-4 border-s-md" :style="mottoStyles">
 "{{ userProfile.student?.personal_motto }}"
 </div>
 </v-col>
 </v-row>
 </v-card>

 <!-- STUDENT DASHBOARD WORKSPACE -->
 <div v-if="activeRole === 'student'">
 <v-row>
 <!-- Career Suggestions -->
 <v-col cols="12" lg="4">
 <v-card class="pa-6 border h-100 d-flex flex-column " elevation="4">
 <div class="mb-6">
 <div class="d-flex align-center justify-space-between mb-4">
 <v-avatar color="primary" size="48" variant="tonal">
 <v-icon size="28">mdi-briefcase-search-outline</v-icon>
 </v-avatar>
 <v-chip color="primary" size="small" variant="flat" class="font-weight-bold">AI Career Guide</v-chip>
 </div>
 <h2 class="text-h5 font-weight-black mb-2">Career Suggestions</h2>
 <p class="text-subtitle-2 text-medium-emphasis">
 Based on your natural aptitudes and skills.
 </p>
 </div>
 
 <div class="flex-grow-1 overflow-y-auto pr-2 extracted-style-2">
 <!-- Loading State -->
 <div v-if="fetchingCareers" class="d-flex flex-column justify-center align-center h-100">
 <v-progress-circular indeterminate color="primary" size="40" class="mb-4"></v-progress-circular>
 <span class="text-body-2 text-medium-emphasis">Analyzing skills...</span>
 </div>
 
 <!-- Empty State -->
 <div v-else-if="!userProfile.student?.skills?.length" class="text-center py-6">
 <v-icon size="40" color="muted" class="mb-3">mdi-alert-circle-outline</v-icon>
 <p class="text-body-2 text-medium-emphasis">No skills recorded yet. Complete assessments to unlock career paths.</p>
 </div>

 <!-- Careers List -->
 <v-list v-else-if="suggestedCareers.length" bg-color="transparent" class="pa-0">
 <v-list-item
 v-for="(career, i) in suggestedCareers"
 :key="i"
 class="px-0 mb-3 rounded-xl border bg-surface-light"
 lines="two"
 >
 <template v-slot:prepend>
 <v-avatar color="primary" variant="tonal" size="36" class="ml-2 mr-3 font-weight-bold">
 {{ i + 1 }}
 </v-avatar>
 </template>
 <v-list-item-title class="font-weight-bold text-body-1">{{ career }}</v-list-item-title>
 <v-list-item-subtitle class="text-caption mt-1">
 Matched from your skills
 </v-list-item-subtitle>
 </v-list-item>
 </v-list>
 
 <!-- API Error / No Results -->
 <div v-else class="text-center py-6">
 <p class="text-body-2 text-medium-emphasis">Unable to fetch suggestions at this time.</p>
 </div>
 </div>
 
 <div class="mt-4 pt-4 border-t">
 <v-btn variant="outlined" color="surface" block to="/dashboard/student-performance" prepend-icon="mdi-chart-timeline-variant" class="font-weight-bold rounded-xl">
 View Cognitive Profile
 </v-btn>
 </div>
 </v-card>
 </v-col>

 <!-- Bloom's Taxonomy Cognitive Mastery (MongoDB) -->
 <v-col cols="12" lg="8">
 <v-card class="pa-6 border h-100 d-flex flex-column" elevation="4">
 <div class="d-flex justify-space-between align-center mb-6">
 <div>
 <h2 class="text-h5 font-weight-black d-flex align-center">
 <v-icon color="secondary" class="mr-2">mdi-brain</v-icon>
 Bloom's Taxonomy Cognitive Breakdown
 </h2>
 
 </div>
 <v-chip color="secondary" variant="tonal" size="small" class="font-weight-bold">Updated: {{ bloomsMastery.last_analyzed }}</v-chip>
 </div>

 <v-row>
 <v-col cols="12" class="mb-2">
 <div class="d-flex justify-space-between text-body-2 font-weight-bold mb-1">
 <span>Remembering (Foundational Recall)</span>
 <span :class="`text-${getProgressColor(bloomsMastery.remembering)}`">{{ bloomsMastery.remembering }}%</span>
 </div>
 <v-progress-linear :model-value="bloomsMastery.remembering" :color="getProgressColor(bloomsMastery.remembering)" height="10" rounded class="bg-surface"></v-progress-linear>
 </v-col>

 <v-col cols="12" class="mb-2">
 <div class="d-flex justify-space-between text-body-2 font-weight-bold mb-1">
 <span>Understanding (Concept Comprehension)</span>
 <span :class="`text-${getProgressColor(bloomsMastery.understanding)}`">{{ bloomsMastery.understanding }}%</span>
 </div>
 <v-progress-linear :model-value="bloomsMastery.understanding" :color="getProgressColor(bloomsMastery.understanding)" height="10" rounded class="bg-surface"></v-progress-linear>
 </v-col>

 <v-col cols="12" class="mb-2">
 <div class="d-flex justify-space-between text-body-2 font-weight-bold mb-1">
 <span>Applying (Formula & Problem Execution)</span>
 <span :class="`text-${getProgressColor(bloomsMastery.applying)}`">{{ bloomsMastery.applying }}%</span>
 </div>
 <v-progress-linear :model-value="bloomsMastery.applying" :color="getProgressColor(bloomsMastery.applying)" height="10" rounded class="bg-surface"></v-progress-linear>
 </v-col>

 <v-col cols="12" class="mb-2">
 <div class="d-flex justify-space-between text-body-2 font-weight-bold mb-1">
 <span>Analyzing (Diagram & Error Deduction)</span>
 <span :class="`text-${getProgressColor(bloomsMastery.analyzing)}`">{{ bloomsMastery.analyzing }}%</span>
 </div>
 <v-progress-linear :model-value="bloomsMastery.analyzing" :color="getProgressColor(bloomsMastery.analyzing)" height="10" rounded class="bg-surface"></v-progress-linear>
 </v-col>

 <v-col cols="12" class="mt-2">
 <div class="d-flex justify-space-between text-body-2 font-weight-bold mb-1">
 <span>Evaluating (Critical Synthesis & Proofs)</span>
 <span :class="`text-${getProgressColor(bloomsMastery.evaluating)}`">{{ bloomsMastery.evaluating }}%</span>
 </div>
 <v-progress-linear :model-value="bloomsMastery.evaluating" :color="getProgressColor(bloomsMastery.evaluating)" height="12" rounded class="bg-surface"></v-progress-linear>
 </v-col>
 </v-row>
 </v-card>
 </v-col>
 </v-row>

 <!-- REPLACED SECTION: Chronological Trend of Student Average Marks Line Graph -->
 <v-card class="pa-6 rounded-3xl border bg-surface mt-8 " elevation="6">
 <div class="d-flex flex-column flex-md-row justify-space-between align-md-center mb-6">
 <div>
 <div class="d-flex align-center gap-2 mb-1">
 <v-chip color="primary" size="small" variant="flat" class="font-weight-bold">LONGITUDINAL ACADEMIC TRAJECTORY</v-chip>
 
 </div>
 <h2 class="text-h4 font-weight-black d-flex align-center">
 <v-icon color="primary" size="32" class="mr-3">mdi-chart-line-variant</v-icon>
 Chronological Trend of Student Average Marks
 </h2>
 
 </div>

 <!-- KPI Summary Cards -->
 <div class="d-flex gap-3 mt-4 mt-md-0">
 <div class="pa-3 rounded-2xl border bg-surface text-center px-5">
 <div class="text-caption text-medium-emphasis font-weight-bold uppercase">Present Cumulative Avg</div>
 <div class="text-h5 font-weight-black text-primary">{{ presentCumulativeAvg }}%</div>
 </div>
 <div class="pa-3 rounded-2xl border bg-surface text-center px-5">
 <div class="text-caption text-medium-emphasis font-weight-bold uppercase">Overall Trajectory Gain</div>
 <div class="text-h5 font-weight-black" :class="Number(overallTrajectoryGain) >= 0 ? 'text-success' : 'text-error'">{{ overallTrajectoryGain }}%</div>
 </div>
 </div>
 </div>

 <!-- Interactive SVG Line & Area Trend Graph -->
 <div class="pa-5 rounded-3xl border bg-surface mb-6 overflow-x-auto">
 <svg :viewBox="`0 0 ${chartMetrics.width} ${chartMetrics.height}`" class="w-100 overflow-visible" style="max-height: 320px; min-width: 680px;">
 <defs>
 <linearGradient id="trendAreaGradient" x1="0%" y1="0%" x2="0%" y2="100%">
 <stop offset="0%" stop-color="#6366f1" stop-opacity="0.45" />
 <stop offset="90%" stop-color="#6366f1" stop-opacity="0.0" />
 </linearGradient>
 </defs>

 <!-- Horizontal Grid Lines -->
 <g v-for="(g, gIdx) in chartMetrics.gridLines" :key="gIdx">
 <line 
 :x1="chartMetrics.padLeft" 
 :y1="g.y" 
 :x2="chartMetrics.width - chartMetrics.padRight" 
 :y2="g.y" 
 stroke="rgba(148, 163, 184, 0.22)" 
 stroke-dasharray="4 4" 
 stroke-width="1"
 />
 <text 
 :x="chartMetrics.padLeft - 10" 
 :y="g.y + 4" 
 text-anchor="end" 
 font-size="11" 
 font-weight="bold" 
 fill="currentColor" 
 opacity="0.6"
 >
 {{ g.val }}
 </text>
 </g>

 <!-- Area Fill Under Trend Curve -->
 <polygon :points="chartMetrics.areaPath" fill="url(#trendAreaGradient)" />

 <!-- Smooth Trend Line -->
 <polyline 
 :points="chartMetrics.linePath" 
 fill="none" 
 stroke="#6366f1" 
 stroke-width="3.5" 
 stroke-linecap="round" 
 stroke-linejoin="round"
 />

 <!-- Data Points & Interactive Circles -->
 <g v-for="pt in chartMetrics.points" :key="pt.id" class="cursor-pointer" @click="activeExamPoint = pt">
 <!-- Hover Guide Line if active -->
 <line 
 v-if="activeExamPoint.id === pt.id"
 :x1="pt.x" 
 :y1="chartMetrics.padTop" 
 :x2="pt.x" 
 :y2="chartMetrics.bottomY" 
 stroke="#6366f1" 
 stroke-dasharray="3 3" 
 stroke-width="1.5"
 opacity="0.7"
 />
 <circle 
 :cx="pt.x" 
 :cy="pt.y" 
 :r="activeExamPoint.id === pt.id ? 8 : 5.5" 
 :fill="activeExamPoint.id === pt.id ? '#ec4899' : '#6366f1'" 
 stroke="#ffffff" 
 :stroke-width="activeExamPoint.id === pt.id ? 3 : 2"
 class="transition-all"
 />
 <!-- Exam Chronology Bottom Labels -->
 <text 
 :x="pt.x" 
 :y="chartMetrics.bottomY + 22" 
 text-anchor="middle" 
 font-size="10.5" 
 font-weight="bold" 
 fill="currentColor" 
 :opacity="activeExamPoint.id === pt.id ? '1.0' : '0.65'"
 >
 E{{ pt.id }}
 </text>
 </g>
 </svg>

 <!-- Timeline Scroller Chips -->
 <div class="d-flex flex-column flex-md-row align-start align-md-center justify-space-between mt-2 pt-3 border-t gap-2">
 <span class="text-caption font-weight-bold text-medium-emphasis mb-2 mb-md-0">Select Exam Checkpoint (E1 - E12):</span>
 <div class="d-flex gap-2 overflow-x-auto py-1 w-100" style="scrollbar-width: none;">
 <v-chip
 v-for="pt in trendExams"
 :key="pt.id"
 :color="activeExamPoint.id === pt.id ? 'primary' : 'surface'"
 :variant="activeExamPoint.id === pt.id ? 'flat' : 'outlined'"
 size="small"
 @click="activeExamPoint = pt"
 class="font-weight-bold cursor-pointer flex-shrink-0"
 >
 E{{ pt.id }}: {{ pt.avg }}%
 </v-chip>
 </div>
 </div>
 </div>

 <!-- Selected Assessment Checkpoint Detail Card -->
 <div v-if="activeExamPoint" class="pa-4 pa-sm-5 rounded-2xl border bg-surface d-flex flex-column justify-space-between">
 <div class="d-flex flex-column flex-sm-row align-sm-center mb-4">
 <v-avatar color="primary" size="52" variant="tonal" class="mr-sm-4 mb-3 mb-sm-0 font-weight-black text-h6 align-self-start align-self-sm-center">
 E{{ activeExamPoint.id }}
 </v-avatar>
 <div class="w-100">
 <div class="d-flex flex-wrap align-center gap-2 mb-1">
 <v-chip v-if="activeExamPoint.exam_code" color="secondary" size="x-small" variant="outlined" class="font-mono font-weight-bold ma-3">{{ activeExamPoint.exam_code }}</v-chip>
 <span class="font-weight-black text-h6" style="line-height: 1.2;">{{ activeExamPoint.name }}</span>
 <v-chip color="success" size="x-small" variant="flat" class="font-weight-bold ma-3">{{ activeExamPoint.grade }} Grade</v-chip>
 </div>
 <div class="text-caption text-medium-emphasis mt-1" style="line-height: 1.4;">
 Period: {{ activeExamPoint.period }} &bull; Stream Benchmark: {{ activeExamPoint.stream_avg }}% &bull; Status: {{ activeExamPoint.status }}
 </div>
 </div>
 </div>

 <div class="d-flex flex-column flex-md-row align-start align-md-center gap-4 pt-3 border-t">
 <div class="w-100">
 <div class="text-caption text-medium-emphasis font-weight-bold uppercase">Student Average vs Stream</div>
 <div class="text-h5 font-weight-black text-primary">
 {{ activeExamPoint.avg }}%
 <span class="text-body-2 font-weight-bold text-success ml-1">(+{{ (activeExamPoint.avg - activeExamPoint.stream_avg).toFixed(1) }}% vs stream)</span>
 </div>
 </div>
 <v-btn color="primary" variant="tonal" @click="openAnalysisDialog" prepend-icon="mdi-file-find-outline" class="rounded-xl font-weight-bold align-self-stretch align-self-md-center ma-3" height="48">
 View AI OCR Analysis
 </v-btn>
 </div>
 </div>
 </v-card>

      <!-- To-Do List for Students -->
      <v-card class="pa-6 rounded-3xl border bg-surface mt-8" elevation="4">
        <div class="d-flex flex-wrap justify-space-between align-center mb-6 gap-3">
          <div>
            <h2 class="text-h5 font-weight-black d-flex align-center">
              <v-icon color="primary" class="mr-2">mdi-checkbox-marked-circle-outline</v-icon>
              Your To-Do List
            </h2>
            <p class="text-caption text-medium-emphasis mb-0">Action items pulled from your exam analyses.</p>
          </div>
          <v-btn size="small" color="primary" variant="tonal" to="/dashboard/student-performance">Full Cognitive Profile</v-btn>
        </div>

        <div v-if="loadingTodos" class="d-flex justify-center pa-6">
          <v-progress-circular indeterminate color="primary"></v-progress-circular>
        </div>
        <div v-else-if="todos.length === 0" class="text-center py-6 text-medium-emphasis">
          No pending action items. Complete an exam to get AI suggestions!
        </div>
        <div v-else v-for="(step, i) in todos" :key="step.id" class="pa-4 rounded-2xl border bg-surface mb-4 d-flex align-center justify-space-between">
          <div class="d-flex align-center w-100">
            <v-checkbox-btn :model-value="step.is_completed" @update:modelValue="toggleTodo(step)" color="primary" class="mr-3 flex-shrink-0"></v-checkbox-btn>
            <div class="flex-grow-1">
              <div class="font-weight-bold text-body-1" :class="step.is_completed ? 'text-decoration-line-through text-medium-emphasis' : ''">
                {{ step.title }}
              </div>
            </div>
            <v-btn icon="mdi-delete" variant="text" size="small" color="error" @click="deleteTodo(step.id)"></v-btn>
          </div>
        </div>
        </v-card>

        <!-- AI OCR Analysis Dialog -->
        <v-dialog v-model="analysisDialog" max-width="800" scrollable>
          <v-card class="rounded-xl bg-surface">
            <v-card-title class="d-flex justify-space-between align-center border-b pa-4">
              <span class="text-h6 font-weight-bold d-flex align-center">
                <v-icon color="primary" class="mr-2">mdi-file-find-outline</v-icon>
                AI OCR Analysis
              </span>
              <v-btn icon="mdi-close" variant="text" @click="analysisDialog = false"></v-btn>
            </v-card-title>
            <v-card-text class="pa-4 pa-sm-6 bg-surface-light">
              <div v-if="fetchingAnalysis" class="d-flex flex-column justify-center align-center py-10">
                <v-progress-circular indeterminate color="primary" size="48" class="mb-4"></v-progress-circular>
                <span class="text-medium-emphasis font-weight-medium">Fetching deep cognitive analysis...</span>
              </div>
              <div v-else-if="analysisResult && analysisResult.error" class="text-center py-10">
                <v-icon size="64" color="warning" class="mb-4">mdi-alert-circle-outline</v-icon>
                <div class="text-h6 font-weight-medium text-medium-emphasis">{{ analysisResult.error }}</div>
              </div>
              <div v-else-if="analysisResult">
                <div class="d-flex justify-space-between align-start mb-6">
                  <div>
                    <v-chip color="success" size="small" variant="flat" prepend-icon="mdi-check-decagram" class="mb-2 font-weight-bold">
                      VISION ANALYSIS COMPLETE
                    </v-chip>
                    <h3 class="text-h5 font-weight-bold">Diagnostic Evaluation & Bloom's Breakdown</h3>
                    <p class="text-caption text-medium-emphasis">Generated for Student ID #{{ analysisResult.student_id }} &bull; Exam ID #{{ analysisResult.exam_id }}</p>
                  </div>
                </div>

                <v-alert color="primary" variant="tonal" icon="mdi-lightbulb-on" border="start" class="mb-6 rounded-lg">
                  <div class="font-weight-bold mb-1">AI Executive Summary</div>
                  <div class="text-body-2" style="line-height: 1.6;">{{ analysisResult.overall_summary }}</div>
                </v-alert>

                <h4 class="text-subtitle-1 font-weight-bold mb-3 d-flex align-center mt-6">
                  <v-icon color="secondary" class="mr-2">mdi-chart-bubble</v-icon> Bloom's Taxonomy Cognitive Mastery (%)
                </h4>
                <v-row class="mb-6">
                  <v-col cols="12" sm="6" v-for="(val, key) in analysisResult.blooms_taxonomy" :key="key">
                    <div class="d-flex justify-space-between text-caption font-weight-bold mb-1 text-capitalize">
                      <span>{{ key }}</span>
                      <span :class="val >= 80 ? 'text-success' : (val >= 60 ? 'text-warning' : 'text-error')">{{ val }}%</span>
                    </div>
                    <v-progress-linear
                      :model-value="val"
                      :color="val >= 80 ? 'success' : (val >= 60 ? 'warning' : 'error')"
                      height="8"
                      rounded
                    ></v-progress-linear>
                  </v-col>
                </v-row>

                <h4 class="text-subtitle-1 font-weight-bold mb-3 d-flex align-center mt-6">
                  <v-icon color="accent" class="mr-2">mdi-format-list-checks</v-icon> Question-Level Diagnostics
                </h4>
                <div class="border rounded-xl overflow-hidden mb-6">
                  <v-table class="bg-surface">
                    <thead class="bg-surface-light">
                      <tr>
                        <th class="font-weight-bold text-left">Q#</th>
                        <th class="text-left">Topic</th>
                        <th class="text-left">Score</th>
                        <th class="text-left">Status</th>
                        <th class="text-left">Comment</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="q in analysisResult.question_breakdown" :key="q.q">
                        <td class="font-weight-bold">Q{{ q.q }}</td>
                        <td><v-chip size="x-small" variant="outlined">{{ q.topic }}</v-chip></td>
                        <td class="font-weight-black text-primary">{{ q.score }}/{{ q.max }}</td>
                        <td>
                          <v-chip size="x-small" :color="q.status === 'Perfect' ? 'success' : 'error'" variant="flat">
                            {{ q.status }}
                          </v-chip>
                        </td>
                        <td class="text-caption text-medium-emphasis">{{ q.comment }}</td>
                      </tr>
                    </tbody>
                  </v-table>
                </div>

                <h4 class="text-subtitle-1 font-weight-bold mb-3 d-flex align-center mt-6">
                  <v-icon color="warning" class="mr-2">mdi-clipboard-list</v-icon> Actionable Revision Steps
                </h4>
                <v-list class="bg-surface rounded-xl border pa-0 mb-6">
                  <v-list-item v-for="(sug, idx) in analysisResult.suggestions" :key="idx" class="pa-3 border-b">
                    <template v-slot:prepend>
                      <v-avatar color="warning" size="24" class="mr-3 text-caption font-weight-bold">
                        {{ idx + 1 }}
                      </v-avatar>
                    </template>
                    <v-list-item-title class="text-body-2 font-weight-medium text-wrap" style="line-height: 1.5;">{{ sug }}</v-list-item-title>
                  </v-list-item>
                </v-list>

                <div v-if="analysisResult.extrapolated_skills && analysisResult.extrapolated_skills.length > 0" class="mt-6 mb-2">
                  <h4 class="text-subtitle-1 font-weight-bold mb-3 d-flex align-center">
                    <v-icon color="success" class="mr-2">mdi-star-circle-outline</v-icon> Extrapolated Skills & Strengths
                  </h4>
                  <div class="d-flex flex-wrap gap-2">
                    <v-chip v-for="(skill, idx) in analysisResult.extrapolated_skills" :key="idx" color="primary" variant="flat" size="small">
                      {{ skill }}
                    </v-chip>
                  </div>
                </div>
              </div>
            </v-card-text>
          </v-card>
        </v-dialog>
     </div>
  
  <!-- PARENT DASHBOARD WORKSPACE -->
 <div v-else-if="activeRole === 'parent'">
 <v-row>
 <v-col cols="12">
 <v-card class="pa-6 rounded-3xl border bg-surface h-100 " elevation="4">
 <div class="d-flex flex-wrap justify-space-between align-center mb-6 gap-3">
 <div>
 <h2 class="text-h5 font-weight-black d-flex align-center">
 <v-icon color="accent" class="mr-2">mdi-home-heart</v-icon>
 Student To-Do List
 </h2>
 <p class="text-caption text-medium-emphasis mb-0">Pending to-do lists for enrolled student(s)</p>
 </div>
 
 <div class="d-flex gap-3 align-center">
 <v-select
 v-if="parentStudents.length > 1"
 v-model="selectedParentStudent"
 :items="parentStudents"
 item-title="user.first_name"
 item-value="id"
 density="compact"
 variant="outlined"
 hide-details
 style="max-width: 150px;"
 >
 <template v-slot:selection="{ item }">
 {{ item.user.first_name }}
 </template>
 <template v-slot:item="{ props, item }">
 <v-list-item v-bind="props" :title="item.user.first_name + ' ' + item.user.last_name"></v-list-item>
 </template>
 </v-select>
 <v-btn size="small" color="primary" variant="outlined" @click="manageChildrenDialog = true" class="ml-2">Manage Children</v-btn>
 <v-btn size="small" color="primary" variant="tonal" to="/dashboard/student-profile-parent" class="ml-2">Full Parent Portal</v-btn>
 </div>
 </div>

 <div v-if="loadingTodos" class="d-flex justify-center pa-6">
 <v-progress-circular indeterminate color="primary"></v-progress-circular>
 </div>
 <div v-else-if="todos.length === 0" class="text-center py-6 text-medium-emphasis">
 No pending action items for this student.
 </div>
 <div v-else v-for="(step, i) in todos" :key="step.id" class="pa-4 rounded-2xl border bg-surface mb-4 d-flex align-center justify-space-between">
 <div class="d-flex align-center">
 <v-checkbox-btn :model-value="step.is_completed" readonly color="success" class="mr-3"></v-checkbox-btn>
 <div>
 <div class="font-weight-bold text-body-1" :class="step.is_completed ? 'text-decoration-line-through text-medium-emphasis' : ''">
 {{ step.title }}
 </div>
 </div>
 </div>
 </div>
 </v-card>
 </v-col>
 </v-row>
 </div>

 <!-- TEACHER DASHBOARD WORKSPACE -->
 <div v-else>
 <v-row>
 <v-col cols="12" md="6">
 <v-card class="pa-6 rounded-3xl border bg-surface h-100 " elevation="4">
 <div class="d-flex justify-space-between align-center mb-4">
 <h2 class="text-h5 font-weight-black d-flex align-center">
 <v-icon color="primary" class="mr-2">mdi-chart-bar</v-icon>
 Stream Bottleneck Overview
 </h2>
 <v-chip color="primary" variant="flat" size="small" class="font-weight-bold ">{{ teacherStreamSummary.class_name }}</v-chip>
 </div>
 <div class="mb-6">
 <div class="text-h3 font-weight-black text-primary">{{ teacherStreamSummary.stream_average }}</div>
 <div class="text-caption text-medium-emphasis">Average stream mastery across your assigned classes</div>
 </div>
 <div class="pa-4 rounded-2xl bg-surface border mb-6">
 <div class="text-caption font-weight-bold text-error uppercase mb-1">Top Pedagogical Bottleneck</div>
 <div class="font-weight-bold text-body-1">{{ teacherStreamSummary.top_error }}</div>
 </div>
 <v-btn color="primary" block size="large" to="/dashboard/class-diagnosis" prepend-icon="mdi-google-circles-extended" class="font-weight-bold rounded-xl">
 Open Class Diagnosis
 </v-btn>
 </v-card>
 </v-col>

 <v-col cols="12" md="6">
 <v-card class="pa-6 rounded-3xl border bg-surface h-100 d-flex flex-column justify-space-between " elevation="4">
 <div>
 <div class="d-flex justify-space-between align-center mb-4">
 <h2 class="text-h5 font-weight-black d-flex align-center">
 <v-icon color="primary" class="mr-2">mdi-file-document-check</v-icon>
 Marking Rubrics & Configurations
 </h2>
 </div>
 <p class="text-subtitle-2 text-medium-emphasis mb-6">
 Configure your custom marking instructions so the system assigns grades to student scripts according to your exact curriculum scheme.
 </p>
 </div>
 <div>
 <v-btn color="primary" block size="large" to="/dashboard/settings" prepend-icon="mdi-cog-outline" class="font-weight-bold rounded-xl mb-3">
 Configure Marking Rubric
 </v-btn>
 <v-btn variant="outlined" color="surface" block to="/admin-dashboard" prepend-icon="mdi-domain" class="font-weight-bold rounded-xl">
 Institutional Admin Oversight
 </v-btn>
 </div>
 </v-card>
 </v-col>
 </v-row>
 
 <!-- New Card for Latest Exam Round -->
 <v-card class="pa-6 rounded-3xl border bg-surface mt-8 " elevation="6" v-if="latestExamMean">
 <div class="d-flex flex-column flex-md-row justify-space-between align-md-center mb-6">
 <div>
 <div class="d-flex align-center gap-2 mb-1">
 <v-chip color="primary" size="small" variant="flat" class="font-weight-bold">EXAM ROUND SUMMARY</v-chip>
 </div>
 <h2 class="text-h4 font-weight-black d-flex align-center mt-2">
 <v-icon color="primary" size="32" class="mr-3">mdi-google-analytics</v-icon>
 Latest Exam Round: {{ latestExamMean.exam_name }}
 </h2>
 </div>
 <div class="d-flex gap-3 mt-4 mt-md-0">
 <div class="pa-3 rounded-2xl border bg-surface text-center px-5">
 <div class="text-caption text-medium-emphasis font-weight-bold uppercase">Class Mean</div>
 <div class="text-h5 font-weight-black text-primary">{{ latestExamMean.mean }}%</div>
 </div>
 </div>
 </div>
 
 <div class="d-flex justify-end">
 <v-btn color="primary" size="large" prepend-icon="mdi-robot-outline" class="font-weight-bold rounded-xl" @click="openAiReportDialog">
 AI Report
 </v-btn>
 </div>
 </v-card>
 </div>

 <!-- AI Report Dialog -->
 <v-dialog v-model="aiReportDialog" max-width="600">
 <v-card class="rounded-3xl border bg-surface pa-2">
 <v-card-title class="text-h5 font-weight-black d-flex align-center mt-4 mx-4">
 <v-icon color="primary" class="mr-2">mdi-robot-outline</v-icon> AI Round Summary
 </v-card-title>
 <v-card-text class="pt-4 mx-4">
 <div class="mb-4 text-body-1">
 This feature summarizes the performance of all your assigned classes for the round: <strong>{{ latestExamMean?.exam_name }}</strong>.
 </div>
 
 <div v-if="fetchingAiReport" class="d-flex flex-column justify-center align-center py-6">
   <v-progress-circular indeterminate color="primary" class="mb-2"></v-progress-circular>
   <span class="text-medium-emphasis">Fetching AI summary...</span>
 </div>
 <div v-else-if="aiReport && aiReport.overall_summary" class="pa-4 rounded-xl bg-surface-light border mb-4">
   <div class="font-weight-bold mb-2"><v-icon size="small" class="mr-2" color="success">mdi-check-circle</v-icon>Overall Impression</div>
   {{ aiReport.overall_summary }}
 </div>
 <div v-else class="pa-4 rounded-xl bg-surface-light border mb-4 text-center">
   <v-icon color="warning" class="mb-2">mdi-alert-circle-outline</v-icon>
   <div>No AI report available yet for this exam round.</div>
 </div>
 
 <div class="text-body-2 text-medium-emphasis">
 To view specific, granular AI analysis per class and subject, please navigate to the <strong>Class Diagnosis</strong> section.
 </div>
 </v-card-text>
 <v-card-actions class="mb-2 mx-4 pb-4">
 <v-spacer></v-spacer>
 <v-btn color="primary" variant="flat" class="rounded-xl px-6 font-weight-bold" to="/dashboard/class-diagnosis" @click="aiReportDialog = false">Go to Class Diagnosis</v-btn>
 <v-btn color="surface" variant="outlined" class="rounded-xl px-6 font-weight-bold" @click="aiReportDialog = false">Close</v-btn>
 </v-card-actions>
 </v-card>
 </v-dialog>

 <!-- MySQL Relational Assessment Table (Shown strictly for Parent and Teacher workspaces) -->
 <v-card v-if="activeRole !== 'student'" class="pa-4 pa-md-6 rounded-3xl border bg-surface mt-8 " elevation="4">
 <div class="d-flex flex-column flex-md-row justify-space-between align-md-center mb-4">
 <div class="mb-3 mb-md-0">
 <h2 class="text-h5 font-weight-black d-flex align-center" style="word-break: break-word;">
 <v-icon color="primary" class="mr-2">mdi-table-check</v-icon>
 Verified Assessment Records
 </h2>
 <p class="text-caption text-medium-emphasis mb-0">Structured ACID grade</p>
 </div>
 <v-btn v-if="activeRole !== 'parent'" color="primary" variant="tonal" to="/dashboard/leaderboard" class="align-self-start align-self-md-center">View Academic Leaderboard</v-btn>
 </div>

 <div class="overflow-x-auto">
 <v-table class="bg-transparent" style="min-width: 800px;">
 <thead>
 <tr>
 <th class="font-weight-bold text-medium-emphasis text-no-wrap">Exam / Assessment Name</th>
 <th class="font-weight-bold text-medium-emphasis text-no-wrap">Date Recorded</th>
 <th class="font-weight-bold text-medium-emphasis text-no-wrap">Score</th>
 <th class="font-weight-bold text-medium-emphasis text-no-wrap">Grade</th>
 <th class="font-weight-bold text-medium-emphasis text-no-wrap">Verification Status</th>
 <th class="font-weight-bold text-medium-emphasis text-right text-no-wrap">Diagnostic Action</th>
 </tr>
 </thead>
 <tbody>
 <tr v-for="exam in recentExams" :key="exam.id">
 <td class="font-weight-bold py-4">
 <div>{{ exam.name || exam.title }}</div>
 <v-chip v-if="exam.exam_code" size="x-small" color="primary" variant="tonal" class="font-mono mt-1">{{ exam.exam_code }}</v-chip>
 </td>
 <td class="text-medium-emphasis py-4 text-no-wrap">{{ exam.date || exam.period }}</td>
 <td class="font-weight-black text-primary py-4 text-no-wrap">{{ exam.score }} / {{ exam.max_score }}</td>
 <td class="py-4">
 <v-chip :color="exam.grade && exam.grade.startsWith('A') ? 'success' : (exam.grade && exam.grade.startsWith('B') ? 'primary' : 'warning')" size="small" variant="flat" class="font-weight-bold">
 {{ exam.grade }}
 </v-chip>
 </td>
 <td class="py-4 text-no-wrap">
 <v-chip :color="exam.status === 'Verified' ? 'success' : 'warning'" size="small" variant="tonal" class="font-weight-bold">
 {{ exam.status }}
 </v-chip>
 </td>
 <td class="py-4 text-right text-no-wrap">
 <v-btn size="small" variant="tonal" color="accent" to="/dashboard/upload-centre" prepend-icon="mdi-eye">AI Vision Report</v-btn>
 </td>
 </tr>
 </tbody>
 </v-table>
 </div>
 </v-card>

   <!-- Manage Children Dialog -->
   <v-dialog v-model="manageChildrenDialog" max-width="600">
     <v-card class="rounded-xl border bg-surface">
       <v-card-title class="font-weight-bold d-flex align-center justify-space-between pt-6 px-6">
         Manage Linked Children
         <v-btn icon="mdi-close" variant="text" size="small" @click="manageChildrenDialog = false"></v-btn>
       </v-card-title>
       <v-card-text class="px-6 pb-6">
         
         <div class="mb-6">
           <h4 class="text-subtitle-2 font-weight-bold mb-3 text-medium-emphasis uppercase">Currently Linked Children</h4>
           <div v-if="parentStudents.length === 0" class="text-caption text-medium-emphasis">No children linked to your profile yet.</div>
           <v-list v-else class="bg-transparent pa-0 border rounded-lg">
             <v-list-item v-for="student in parentStudents" :key="student.id" class="border-b py-3">
               <template v-slot:prepend>
                 <v-avatar color="accent" size="36" class="mr-3 text-caption">
                   {{ student.user?.first_name[0] || 'S' }}
                 </v-avatar>
               </template>
               <v-list-item-title class="font-weight-bold">
                 {{ student.user?.first_name }} {{ student.user?.last_name }}
               </v-list-item-title>
               <v-list-item-subtitle class="text-caption font-mono">
                 ADM: {{ student.admission_number }}
               </v-list-item-subtitle>
               <template v-slot:append>
                 <v-btn icon="mdi-delete" color="error" variant="text" size="small" :loading="isUnlinking" @click="unlinkStudent(student.id)"></v-btn>
               </template>
             </v-list-item>
           </v-list>
         </div>

         <v-divider class="mb-6"></v-divider>

         <h4 class="text-subtitle-2 font-weight-bold mb-3 text-medium-emphasis uppercase">Link a New Child</h4>
         <div class="d-flex align-center mb-4">
           <v-text-field
             v-model="searchQuery"
             label="Search by Name or Admission No."
             variant="outlined"
             density="comfortable"
             hide-details
             class="mr-3"
             @keyup.enter="searchStudents"
           ></v-text-field>
           <v-btn color="primary" @click="searchStudents" :loading="isSearching">Search</v-btn>
         </div>
         
         <div v-if="searchResults.length === 0 && searchQuery && !isSearching" class="text-caption text-error text-center py-4">
           No students found matching your query.
         </div>
         
         <v-list v-else-if="searchResults.length > 0" class="bg-transparent pa-0 border rounded-lg">
           <v-list-item v-for="student in searchResults" :key="student.id" class="border-b py-3">
             <template v-slot:prepend>
               <v-avatar color="secondary" size="36" class="mr-3 text-caption">
                 {{ student.user?.first_name[0] || 'S' }}
               </v-avatar>
             </template>
             <v-list-item-title class="font-weight-bold">
               {{ student.user?.first_name }} {{ student.user?.last_name }}
             </v-list-item-title>
             <v-list-item-subtitle class="text-caption font-mono">
               ADM: {{ student.admission_number }}
             </v-list-item-subtitle>
             <template v-slot:append>
               <v-btn size="small" color="primary" variant="flat" :loading="isLinking" @click="linkStudent(student.id)" :disabled="parentStudents.some(s => s.id === student.id)">
                 {{ parentStudents.some(s => s.id === student.id) ? 'Linked' : 'Link' }}
               </v-btn>
             </template>
           </v-list-item>
         </v-list>
       </v-card-text>
     </v-card>
   </v-dialog>

 </v-container>
</template>

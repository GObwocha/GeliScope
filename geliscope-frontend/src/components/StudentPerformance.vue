<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import axios from 'axios'
import { useAuthStore } from '@/stores/auth'
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement
} from 'chart.js'
import { Bar, Line } from 'vue-chartjs'

ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend, PointElement, LineElement)

const authStore = useAuthStore()
const activeRole = computed(() => authStore.activeRole || 'student')
const graphType = ref('bar')

const displayData = computed(() => {
 if (activeRole.value === 'parent') {
 return {
 title: "Keep Your Children's Success",
 subtitle: "Grading in education",
 grade: "C,",
 percent: "87%",
 updateDate: "January 29",
 contactsTitle: "Teacher Contacts",
 contactsSubtitle: "Here you can see teachers and their contacts.",
 contacts: [
 { name: "John Kamau", role: "Mathematics", phone: "+254 712 345 678", avatar: "J" },
 { name: "Mary Wanjiku", role: "Physics", phone: "+254 723 456 789", avatar: "M" },
 { name: "Peter Ochieng", role: "Chemistry", phone: "+254 734 567 890", avatar: "P" }
 ],
 upcomingTests: [
 { day: '22', weekday: 'Monday', active: false },
 { day: '23', weekday: 'Tuesday', active: true, title: 'Math' },
 { day: '24', weekday: 'Wednesday', active: false, title: 'Art' },
 { day: '25', weekday: 'Thursday', active: false },
 { day: '26', weekday: 'Friday', active: false }
 ],
 schedule: [
 { time: "8:45 AM - 10:10 AM", subject: "English Language Arts" },
 { time: "10:13 AM - 10:54 AM", subject: "Physical Education" },
 { time: "10:57 AM - 11:37 AM", subject: "Mathematics" },
 { time: "11:40 AM - 12:20 PM", subject: "Lunch & Advisory" },
 { time: "12:23 PM - 1:03 PM", subject: "Enrichment" },
 { time: "1:06 PM - 1:47 PM", subject: "Science" }
 ],
 strengths: [
 { title: 'Algebraic Formulation', detail: 'Flawless execution of quadratic equations.' },
 { title: 'Logical Deduction', detail: 'Strong performance in step transformations.' }
 ],
 weaknesses: [
 { title: 'Spatial Reasoning', detail: 'Struggles with 3D projection diagrams.' },
 { title: 'Critical Evaluation', detail: 'Focus on open-ended proof word problems.' }
 ]
 }
 } else if (activeRole.value === 'student') {
 return {
 title: "Keep Track of Your Success",
 subtitle: "Personal grading overview",
 grade: "A,",
 percent: "92%",
 updateDate: "January 29",
 contactsTitle: "Study Group",
 contactsSubtitle: "Here you can see your peers and their contacts.",
 contacts: [
 { name: "Brian Otieno", role: "Class President", phone: "+254 711 111 111", avatar: "B" },
 { name: "Alice Njoroge", role: "Math Club Head", phone: "+254 722 222 222", avatar: "A" },
 { name: "Kevin Kiprop", role: "Lab Partner", phone: "+254 733 333 333", avatar: "K" }
 ],
 upcomingTests: [
 { day: '22', weekday: 'Monday', active: false },
 { day: '23', weekday: 'Tuesday', active: true, title: 'Math' },
 { day: '24', weekday: 'Wednesday', active: false, title: 'Art' },
 { day: '25', weekday: 'Thursday', active: false },
 { day: '26', weekday: 'Friday', active: false }
 ],
 schedule: [
 { time: "8:45 AM - 10:10 AM", subject: "English Language Arts" },
 { time: "10:13 AM - 10:54 AM", subject: "Physical Education" },
 { time: "10:57 AM - 11:37 AM", subject: "Mathematics" },
 { time: "11:40 AM - 12:20 PM", subject: "Lunch" },
 { time: "12:23 PM - 1:03 PM", subject: "Chemistry Lab" }
 ],
 strengths: [
 { title: 'Algebraic Formulation', detail: 'Consistent high marks in algebra.' },
 { title: 'Logical Deduction', detail: 'Quickly solves logic puzzles.' }
 ],
 weaknesses: [
 { title: 'Spatial Reasoning', detail: 'Needs more practice with geometry.' },
 { title: 'Critical Evaluation', detail: 'Can improve on essay structuring.' }
 ]
 }
 } else {
 // Teacher
 return {
 title: "Stream Average Success",
 subtitle: "Form 2 East overview",
 grade: "B-,",
 percent: "74%",
 updateDate: "January 29",
 contactsTitle: "Parent Contacts",
 contactsSubtitle: "Here you can see parents and their contacts.",
 contacts: [
 { name: "David Otieno", role: "Parent (Brian)", phone: "+254 799 999 999", avatar: "D" },
 { name: "Susan Njoroge", role: "Parent (Alice)", phone: "+254 788 888 888", avatar: "S" },
 { name: "Michael Kiprop", role: "Parent (Kevin)", phone: "+254 777 777 777", avatar: "M" }
 ],
 upcomingTests: [
 { day: '22', weekday: 'Monday', active: false },
 { day: '23', weekday: 'Tuesday', active: true, title: 'Marking' },
 { day: '24', weekday: 'Wednesday', active: false, title: 'Dept Mtg' },
 { day: '25', weekday: 'Thursday', active: false },
 { day: '26', weekday: 'Friday', active: false }
 ],
 schedule: [
 { time: "8:00 AM - 9:30 AM", subject: "Staff Briefing" },
 { time: "9:30 AM - 11:00 AM", subject: "F2 East - Math" },
 { time: "11:30 AM - 1:00 PM", subject: "F3 West - Math" },
 { time: "2:00 PM - 3:30 PM", subject: "Lesson Prep" }
 ],
 strengths: [
 { title: 'Stream Algebra Avg', detail: 'Class average is 82% in algebraic formulation.' },
 { title: 'Stream Logic Avg', detail: 'Logical deduction concepts well grasped.' }
 ],
 weaknesses: [
 { title: 'Stream Spatial Avg', detail: 'Only 54% average in 3D geometry.' },
 { title: 'Stream Evaluation', detail: 'Word problems need more focus.' }
 ]
 }
 }
})

// Chronological Exam Data
const examHistory = ref([])
const loadingHistory = ref(true)
const subjects = ref([{ id: '', name: 'Overall (All Subjects)' }])
const selectedSubject = ref('')

// Teacher specific state
const teacherClasses = ref([])
const selectedClass = ref(null)
const classPerformanceData = ref({ rounds: [], subjects: [] })

const fetchTeacherClasses = async () => {
  if (activeRole.value !== 'teacher') return
  const teacherId = authStore.currentUser?.teacher?.id || authStore.currentUser?.id
  try {
    const res = await axios.get(`/api/v1/analysis/teacher/${teacherId}/classes`)
    teacherClasses.value = res.data
    if (res.data.length > 0) {
      selectedClass.value = res.data[0].id
      fetchClassPerformance()
    }
  } catch (err) {
    console.error('Failed to fetch teacher classes', err)
  }
}

const fetchClassPerformance = async () => {
  if (activeRole.value !== 'teacher' || !selectedClass.value) return
  const teacherId = authStore.currentUser?.teacher?.id || authStore.currentUser?.id
  try {
    loadingHistory.value = true
    const res = await axios.get(`/api/v1/analysis/teacher/${teacherId}/class-performance?class_id=${selectedClass.value}`)
    classPerformanceData.value = res.data
  } catch (err) {
    console.error('Failed to fetch class performance', err)
  } finally {
    loadingHistory.value = false
  }
}

const classChartData = computed(() => {
  const datasets = classPerformanceData.value.subjects.map((subj, idx) => {
    const hue = (idx * 137.5) % 360; 
    return {
      label: subj.name,
      data: subj.data,
      backgroundColor: `hsla(${hue}, 70%, 50%, 0.5)`,
      borderColor: `hsl(${hue}, 70%, 50%)`,
      borderWidth: 2,
      tension: 0.3
    }
  });

  return {
    labels: classPerformanceData.value.rounds,
    datasets
  }
})

const classChartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  scales: {
    y: {
      beginAtZero: true,
      max: 100
    }
  }
}

const fetchExamHistory = async () => {
  try {
    loadingHistory.value = true
    const studentId = authStore.currentUser?.student?.id
    if (!studentId) return

    let url = `/api/v1/students/${studentId}/exam-results`
    if (selectedSubject.value) {
      url += `?subject_id=${selectedSubject.value}`
    }

    const res = await axios.get(url)
    if (res.data) {
      examHistory.value = res.data.map(result => {
        return {
          id: `RND-${result.id}`, // using round ID
          name: result.exam?.name || 'Unknown',
          score: result.total_score ? Math.round((parseFloat(result.total_score) / parseFloat(result.max_score)) * 100) : result.avg || 0
        }
      })
    }
  } catch (err) {
    console.error('Failed to fetch real exam history:', err)
  } finally {
    loadingHistory.value = false
  }
}

onMounted(async () => {
  try {
    const subjRes = await axios.get('/api/v1/subjects')
    if (subjRes.data) {
      subjects.value = [
        { id: '', name: 'Overall (All Subjects)' },
        ...subjRes.data
      ]
    }
  } catch(e) {
    console.error('Failed to load subjects', e)
  }

  if (activeRole.value === 'teacher') {
    fetchTeacherClasses()
  } else {
    fetchExamHistory()
  }
})

const chartMetrics = computed(() => {
 if (examHistory.value.length === 0) {
 return { points: [], maxScore: 0, minScore: 0, polylineStr: '', width: 400, height: 150 }
 }

 const scores = examHistory.value.map(e => e.score)
 const maxScore = Math.max(...scores)
 const minScore = Math.min(...scores)
 
 // Define SVG dimensions for Line graph
 const width = 400
 const height = 150
 const padLeft = 20
 const padRight = 20
 const padTop = 15
 const padBottom = 25
 
 const plotWidth = width - padLeft - padRight
 const plotHeight = height - padTop - padBottom
 
 const points = examHistory.value.map((e, idx) => {
 // Prevent division by zero if there's only 1 exam
 const xProgress = examHistory.value.length > 1 ? (idx / (examHistory.value.length - 1)) : 0.5
 const x = padLeft + (xProgress * plotWidth)
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
</script>

<template>
 <v-container fluid class="bg-background pa-6 font-family-sans">

 <!-- Top Section: Metric, Chart, Contacts -->
 <!-- <v-row class="mb-8"> -->
 
 <!-- Big Metric -->
 <v-row class="d-flex flex-column px-6">
 <v-card class=" pa-5 border d-flex flex-column justify-center">
 <h1 class="text-h3 font-weight-medium lh-tight mb-6">
 {{ displayData.title }}
 </h1>
 <div class="text-caption text-medium-emphasis mb-1">{{ displayData.subtitle }}</div>
 <div class="d-flex align-baseline gap-2 mb-2">
 <span class="text-h1 font-weight-regular">{{ displayData.grade }}</span>
 <span class="text-h1 font-weight-regular">{{ displayData.percent }}</span>
 </div>
 <div class="text-caption text-medium-emphasis font-weight-medium">
 Latest update added: <span class="text-high-emphasis font-weight-bold">{{ displayData.updateDate }}</span>
 </div>
 </v-card>
 </v-row>
 <!-- Bar / Line Chart -->
 <v-row class="px-6 d-flex flex-column">
 <v-card class=" pa-5 border d-flex flex-column justify-center">
 <div class="d-flex flex-wrap align-center justify-space-between mb-4 gap-4">
 <div class="text-h6 font-weight-bold">Average Marks</div>
 <div class="d-flex align-center gap-4">
 <v-select
 v-if="activeRole === 'teacher'"
 v-model="selectedClass"
 :items="teacherClasses"
 item-title="name"
 item-value="id"
 density="compact"
 variant="outlined"
 hide-details
 @update:modelValue="fetchClassPerformance"
 style="max-width: 200px"
 ></v-select>
 <v-select
 v-else
 v-model="selectedSubject"
 :items="subjects"
 item-title="name"
 item-value="id"
 density="compact"
 variant="outlined"
 hide-details
 @update:modelValue="fetchExamHistory"
 style="max-width: 200px"
 ></v-select>
 <v-btn-toggle v-model="graphType" mandatory color="primary" variant="outlined" class="rounded-lg bg-surface" >
 <v-btn value="table" icon="mdi-table" size="small" class="mr-4"></v-btn>
 <v-btn value="bar" icon="mdi-chart-bar" size="small" class="mr-4"></v-btn>
 <v-btn value="line" icon="mdi-chart-line-variant" size="small" class="mr-4"></v-btn>
 </v-btn-toggle>
 </div>
 </div>
 
 <!-- Custom Visualization -->
 <div class="chart-container flex-grow-1 d-flex flex-column justify-end pt-4" >
 
 <!-- Teacher View -->
 <template v-if="activeRole === 'teacher'">
   <div v-if="graphType === 'table'" class="w-100 h-100 overflow-auto">
     <v-table density="compact" class="border">
       <thead>
         <tr>
           <th class="font-weight-bold">Subject</th>
           <th v-for="(round, idx) in classPerformanceData.rounds" :key="idx" class="font-weight-bold text-center">{{ round }}</th>
         </tr>
       </thead>
       <tbody>
         <tr v-for="(subj, idx) in classPerformanceData.subjects" :key="idx">
           <td class="font-weight-medium">{{ subj.name }}</td>
           <td v-for="(score, sIdx) in subj.data" :key="sIdx" class="text-center">
             {{ score !== null ? score + '%' : '-' }}
           </td>
         </tr>
       </tbody>
     </v-table>
   </div>
   <div v-else-if="graphType === 'bar'" class="w-100 h-100 position-relative" style="min-height: 250px;">
     <Bar :data="classChartData" :options="classChartOptions" />
   </div>
   <div v-else-if="graphType === 'line'" class="w-100 h-100 position-relative" style="min-height: 250px;">
     <Line :data="classChartData" :options="classChartOptions" />
   </div>
 </template>

 <!-- Student/Parent View -->
 <template v-else>
 <!-- Bar Graph -->
 <div v-if="graphType === 'bar'" class="d-flex justify-space-between align-end h-100 pb-2 px-2 gap-4">
 <div v-for="(exam, i) in chartMetrics.points" :key="i" class="d-flex flex-column align-center w-100 h-100 justify-end position-relative group">
 <!-- Score label -->
 <div class="text-caption font-weight-bold mb-1 opacity-80" :class="getBarColor(exam.score).replace('bg-', 'text-')">{{ exam.score }}%</div>
 
 <!-- Dynamic Bar -->
 <div class="w-100 rounded-t transition-all" 
 :class="[getBarColor(exam.score).split(' ')[0]]"
 :style="`height: ${exam.score}%; max-width: 36px;`"></div>
 
 <div class="text-caption text-medium-emphasis font-weight-bold mt-2">{{ exam.id }}</div>
 </div>
 </div>
 
 <!-- Line Graph -->
 <div v-else-if="graphType === 'line'" class="h-100 w-100 position-relative pb-0 d-flex flex-column justify-end">
 <svg :viewBox="`0 0 ${chartMetrics.width} ${chartMetrics.height}`" class="w-100 overflow-visible" style="max-height: 180px;">
 <!-- Connecting Line -->
 <polyline 
 :points="chartMetrics.polylineStr" 
 fill="none" 
 stroke="currentColor" 
 stroke-width="3" 
 stroke-linecap="round" 
 stroke-linejoin="round" 
 class="text-primary" 
 />
 <!-- Data Points -->
 <g v-for="(p, i) in chartMetrics.points" :key="i">
 <circle 
 :cx="p.x" 
 :cy="p.y" 
 :r="p.isMax || p.isMin ? 6 : 4" 
 fill="currentColor" 
 class="text-surface" 
 stroke="currentColor" 
 stroke-width="2.5" 
 :class="getBarColor(p.score).split(' ')[1]" 
 />
 <!-- Data Label -->
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
 <!-- X-Axis Label -->
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
 </template>
 
 </div>
 </v-card>
 </v-row>

 <!-- Contacts List -->
 <v-row class="px-6 d-flex flex-column">
 <v-card class=" pa-5  border h-100 d-flex flex-column">
 <div class="text-h6 font-weight-regular mb-1">{{ displayData.contactsTitle }}</div>
 <div class="text-caption text-medium-emphasis mb-6">{{ displayData.contactsSubtitle }}</div>
 
 <div class="d-flex flex-column gap-4 flex-grow-1">
 <div v-for="(contact, i) in displayData.contacts" :key="i" class="d-flex align-center justify-space-between">
 <div class="d-flex align-center gap-3">
 <v-avatar size="36" color="primary" variant="tonal">
 <span class="font-weight-bold text-caption">{{ contact.avatar }}</span>
 </v-avatar>
 <div>
 <div class="text-body-2 font-weight-medium">{{ contact.name }}</div>
 <div class="text-caption text-medium-emphasis">{{ contact.role }}</div>
 </div>
 </div>
 <div class="text-caption font-weight-medium">{{ contact.phone }}</div>
 </div>
 </div>
 
 <v-btn size="small" variant="flat" color="surface" class="mt-6 elevation-1 font-weight-bold rounded-lg px-4 w-100">
 Show all
 </v-btn>
 </v-card>
 </v-row>
 
 </v-container>
</template>

<style scoped>
.extracted-style-1 {
 max-width: 300px
}

.extracted-style-2 {
 left: 0; pointer-events: none;
}

.extracted-style-3 {
 z-index: 1;
}

.extracted-style-4 {
 min-height: 200px; border-bottom: none;
}

.extracted-style-5 {
 font-size: 10px !important;
}

.extracted-style-6 {
 max-height: 180px
}

.extracted-style-7 {
 opacity: 0.1;
}

/* Theme-aware aesthetic styling */

.lh-tight {
 line-height: 1.1 !important;
}

.lh-relaxed {
 line-height: 1.6 !important;
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

.transition-all {
 transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.dot {
 width: 6px;
 height: 6px;
 border-radius: 50%;
 display: inline-block;
}

.bg-high-emphasis {
 background-color: rgba(var(--v-theme-on-background), 0.9) !important;
 color: rgb(var(--v-theme-background)) !important;
}

.text-high-emphasis {
 color: rgba(var(--v-theme-on-background), 0.9) !important;
}

/* Custom Chart Styling */
.chart-container {
 border-bottom: 1px solid rgba(var(--v-border-color), var(--v-border-opacity));
}

.border-right {
 border-right: 1px solid rgba(var(--v-border-color), 0.08);
}

.border-left {
 border-left: 1px solid rgba(var(--v-border-color), 0.08);
}

.border-bottom-light {
 border-bottom: 1px solid rgba(var(--v-border-color), 0.05);
}

.opacity-80 {
 opacity: 0.8;
}

/* Font Family to mimic the modern aesthetic */
.font-family-sans {
 font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}
</style>

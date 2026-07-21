<script setup>
import { ref, onMounted, computed } from 'vue'
import { useAuthStore } from '@/stores/auth'

const router = useRouter()
const authStore = useAuthStore()

// Default / active role view switcher ('student' | 'parent' | 'teacher')
const activeRole = computed(() => authStore.activeRole || 'student')
const loading = ref(true)

// User Identity State
const userProfile = computed(() => {
  return authStore.currentUser || {
    first_name: 'Guest',
    last_name: 'User',
    email: '',
    role: 'student',
    school: { name: 'Unknown School', county: '' },
    class_group: 'Unknown Class'
  }
})

// Chronological Trend of Student Average Marks (`exam_results` table sync across all historical terms)
const trendExams = ref([
  { id: 1, exam_code: 'EX-25-T1-OP', name: 'Form 1 - Term 1 Opener', period: 'Jan 2025', avg: 58.0, grade: 'C+', stream_avg: 54.2, status: 'Verified' },
  { id: 2, exam_code: 'EX-25-T1-MT', name: 'Form 1 - Mid-Term 1', period: 'Feb 2025', avg: 64.5, grade: 'B-', stream_avg: 59.0, status: 'Verified' },
  { id: 3, exam_code: 'EX-25-T1-END', name: 'Form 1 - End Term 1', period: 'Apr 2025', avg: 67.2, grade: 'B', stream_avg: 61.5, status: 'Verified' },
  { id: 4, exam_code: 'EX-25-T2-OP', name: 'Form 1 - Term 2 Opener', period: 'May 2025', avg: 62.0, grade: 'B-', stream_avg: 58.8, status: 'Verified' },
  { id: 5, exam_code: 'EX-25-T2-MT', name: 'Form 1 - Mid-Term 2', period: 'Jun 2025', avg: 71.4, grade: 'B+', stream_avg: 64.0, status: 'Verified' },
  { id: 6, exam_code: 'EX-25-T2-END', name: 'Form 1 - End Term 2', period: 'Aug 2025', avg: 74.0, grade: 'B+', stream_avg: 66.2, status: 'Verified' },
  { id: 7, exam_code: 'EX-25-T3-FIN', name: 'Form 1 - Term 3 Final', period: 'Nov 2025', avg: 76.8, grade: 'A-', stream_avg: 67.5, status: 'Verified' },
  { id: 8, exam_code: 'EX-26-T1-OP', name: 'Form 2 - Term 1 Opener', period: 'Jan 2026', avg: 73.5, grade: 'B+', stream_avg: 65.0, status: 'Verified' },
  { id: 9, exam_code: 'EX-26-T1-MT', name: 'Form 2 - Mid-Term 1', period: 'Feb 2026', avg: 79.2, grade: 'A-', stream_avg: 68.4, status: 'Verified' },
  { id: 10, exam_code: 'EX-26-T1-END', name: 'Form 2 - End Term 1', period: 'Apr 2026', avg: 82.0, grade: 'A', stream_avg: 70.1, status: 'Verified' },
  { id: 11, exam_code: 'EX-26-T2-OP', name: 'Form 2 - Term 2 Opener', period: 'May 2026', avg: 80.5, grade: 'A-', stream_avg: 69.8, status: 'Verified' },
  { id: 12, exam_code: 'EX-26-T2-MT2', name: 'Form 2 - Mid-Term 2 (Present)', period: 'Jul 2026', avg: 85.4, grade: 'A', stream_avg: 71.2, status: 'Verified' }
])

const activeExamPoint = ref(trendExams.value[trendExams.value.length - 1])

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
  
  const points = trendExams.value.map((item, idx) => {
    const x = padLeft + (idx / (trendExams.value.length - 1)) * plotWidth
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

// MySQL Relational Assessment Data (`exam_results`) for Parent and Teacher views
const recentExams = ref([
  { id: 101, exam_code: 'EXAM-2026-MATH-01', title: 'Term 2 Mid-Term Mathematics', score: 88, max_score: 100, grade: 'A', date: '2026-07-10', status: 'Verified' },
  { id: 102, exam_code: 'EXAM-2026-PHYS-01', title: 'Term 2 Mid-Term Physics', score: 76, max_score: 100, grade: 'A-', date: '2026-07-08', status: 'Verified' },
  { id: 103, exam_code: 'EXAM-2026-CHEM-01', title: 'Term 2 Mid-Term Chemistry', score: 62, max_score: 100, grade: 'B', date: '2026-07-05', status: 'Needs Revision' }
])

// MongoDB Unstructured AI Diagnostics (`exam_analyses` & `student_skill_profiles`)
const bloomsMastery = ref({
  remembering: 92,
  understanding: 85,
  applying: 78,
  analyzing: 68,
  evaluating: 54,
  last_analyzed: '2 hours ago'
})

const parentActionSteps = ref([
  { title: 'Trigonometric Sine Rule Practice', priority: 'High Priority', detail: 'Review triangle opposite vs. hypotenuse ratios before Sunday evening.', completed: false },
  { title: 'Quadratic Equation Sign Flips', priority: 'Medium Priority', detail: 'Complete 5 practice questions focusing on negative square roots.', completed: true },
  { title: 'Vector Addition Scalar Multiplication', priority: 'Good Mastery', detail: 'Child scored 90% on recent diagnostics. Keep up consistent review.', completed: true }
])

const teacherStreamSummary = ref({
  class_name: 'Form 2 East (Mathematics)',
  total_students: 45,
  stream_average: '68.4%',
  top_error: 'Quadratic Factorization Sign Derivation (62% of stream)',
  pending_scans: 8
})

// Load active session from local storage or backend API
onMounted(() => {
  if (!authStore.isAuthenticated) {
    router.push('/login')
  } else {
    loading.value = false
  }
})


<template>
  <v-container class="py-8">
    <!-- Top Welcome Banner & Role Switcher -->
    <v-card class="pa-6 rounded-3xl border bg-cardBg shadow-glow mb-8" elevation="4">
      <v-row align="center">
        <v-col cols="12" md="12" class="d-flex align-center">
          <v-avatar :color="activeRole === 'student' ? 'primary' : (activeRole === 'teacher' ? 'secondary' : 'accent')" size="76" class="mr-6 font-weight-black text-h4 shadow-glow">
            <span class="text-white">{{ userProfile.first_name[0] }}{{ userProfile.last_name[0] }}</span>
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
      </v-row>
    </v-card>

    <!-- STUDENT DASHBOARD WORKSPACE -->
    <div v-if="activeRole === 'student'">
      <v-row>
        <!-- Quick Actions & OCR Launch -->
        <v-col cols="12" lg="4">
          <v-card class="pa-6 rounded-3xl border bg-cardBg h-100 d-flex flex-column justify-space-between shadow-glow" elevation="4">
            <div>
              <div class="d-flex align-center justify-space-between mb-4">
                <v-avatar color="primary" size="48" variant="tonal">
                  <v-icon size="28">mdi-camera</v-icon>
                </v-avatar>
                <v-chip color="primary" size="small" variant="flat" class="font-weight-bold">Vision AI</v-chip>
              </div>
              <h2 class="text-h5 font-weight-black mb-2">Scan & Evaluate Exam Script</h2>
              <p class="text-subtitle-2 text-medium-emphasis mb-6">
                Upload your handwritten paper (.jpg, .png, .pdf) for instant diagram OCR extraction and question-by-question Bloom's taxonomy breakdown.
              </p>
            </div>
            <div>
              <v-btn color="primary" size="large" block to="/dashboard/upload-centre" prepend-icon="mdi-cloud-upload" elevation="4" class="py-4 font-weight-bold rounded-xl shadow-glow mb-3">
                Launch Vision OCR Scanner
              </v-btn>
              <v-btn variant="outlined" color="surface" block to="/dashboard/student-performance" prepend-icon="mdi-chart-timeline-variant" class="font-weight-bold rounded-xl">
                View Cognitive Profile
              </v-btn>
            </div>
          </v-card>
        </v-col>

        <!-- Bloom's Taxonomy Cognitive Mastery (MongoDB) -->
        <v-col cols="12" lg="8">
          <v-card class="pa-6 rounded-3xl border bg-cardBg h-100 shadow-glow" elevation="4">
            <div class="d-flex justify-space-between align-center mb-6">
              <div>
                <h2 class="text-h5 font-weight-black d-flex align-center">
                  <v-icon color="secondary" class="mr-2">mdi-brain</v-icon>
                  Bloom's Taxonomy Cognitive Breakdown
                </h2>
                <p class="text-caption text-medium-emphasis mb-0">Unstructured AI diagnostics synced from MongoDB (exam_analyses collection)</p>
              </div>
              <v-chip color="secondary" variant="tonal" size="small" class="font-weight-bold">Updated: {{ bloomsMastery.last_analyzed }}</v-chip>
            </div>

            <v-row>
              <v-col cols="12" sm="6" class="mb-2">
                <div class="d-flex justify-space-between text-body-2 font-weight-bold mb-1">
                  <span>Remembering (Foundational Recall)</span>
                  <span class="text-success">{{ bloomsMastery.remembering }}%</span>
                </div>
                <v-progress-linear :model-value="bloomsMastery.remembering" color="success" height="10" rounded class="bg-surface"></v-progress-linear>
              </v-col>

              <v-col cols="12" sm="6" class="mb-2">
                <div class="d-flex justify-space-between text-body-2 font-weight-bold mb-1">
                  <span>Understanding (Concept Comprehension)</span>
                  <span class="text-success">{{ bloomsMastery.understanding }}%</span>
                </div>
                <v-progress-linear :model-value="bloomsMastery.understanding" color="success" height="10" rounded class="bg-surface"></v-progress-linear>
              </v-col>

              <v-col cols="12" sm="6" class="mb-2">
                <div class="d-flex justify-space-between text-body-2 font-weight-bold mb-1">
                  <span>Applying (Formula & Problem Execution)</span>
                  <span class="text-primary">{{ bloomsMastery.applying }}%</span>
                </div>
                <v-progress-linear :model-value="bloomsMastery.applying" color="primary" height="10" rounded class="bg-surface"></v-progress-linear>
              </v-col>

              <v-col cols="12" sm="6" class="mb-2">
                <div class="d-flex justify-space-between text-body-2 font-weight-bold mb-1">
                  <span>Analyzing (Diagram & Error Deduction)</span>
                  <span class="text-warning">{{ bloomsMastery.analyzing }}%</span>
                </div>
                <v-progress-linear :model-value="bloomsMastery.analyzing" color="warning" height="10" rounded class="bg-surface"></v-progress-linear>
              </v-col>

              <v-col cols="12" class="mt-2">
                <div class="d-flex justify-space-between text-body-2 font-weight-bold mb-1">
                  <span>Evaluating (Critical Synthesis & Proofs)</span>
                  <span class="text-error">{{ bloomsMastery.evaluating }}% (Target Focus Area)</span>
                </div>
                <v-progress-linear :model-value="bloomsMastery.evaluating" color="error" height="12" rounded class="bg-surface"></v-progress-linear>
              </v-col>
            </v-row>
          </v-card>
        </v-col>
      </v-row>

      <!-- REPLACED SECTION: Chronological Trend of Student Average Marks Line Graph -->
      <v-card class="pa-6 rounded-3xl border bg-cardBg mt-8 shadow-glow" elevation="6">
        <div class="d-flex flex-column flex-md-row justify-space-between align-md-center mb-6">
          <div>
            <div class="d-flex align-center gap-2 mb-1">
              <v-chip color="primary" size="small" variant="flat" class="font-weight-bold">LONGITUDINAL ACADEMIC TRAJECTORY</v-chip>
              <v-chip color="success" size="small" variant="tonal" class="font-weight-bold ma-5">ALL 12 EXAMS INCLUDED</v-chip>
            </div>
            <h2 class="text-h4 font-weight-black d-flex align-center">
              <v-icon color="primary" size="32" class="mr-3">mdi-chart-line-variant</v-icon>
              Chronological Trend of Student Average Marks (exam_results)
            </h2>
            <p class="text-subtitle-2 text-medium-emphasis mb-0 mt-1">
              Tracks continuous overall average mark performance across every term assessment from Form 1 Opener up until presently (`85.4%`).
            </p>
          </div>

          <!-- KPI Summary Cards -->
          <div class="d-flex gap-3 mt-4 mt-md-0">
            <div class="pa-3 rounded-2xl border bg-surface text-center px-5">
              <div class="text-caption text-medium-emphasis font-weight-bold uppercase">Present Cumulative Avg</div>
              <div class="text-h5 font-weight-black text-primary">85.4%</div>
            </div>
            <div class="pa-3 rounded-2xl border bg-surface text-center px-5">
              <div class="text-caption text-medium-emphasis font-weight-bold uppercase">Overall Trajectory Gain</div>
              <div class="text-h5 font-weight-black text-success">+27.4%</div>
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
          <div class="d-flex align-center justify-space-between mt-2 pt-3 border-t">
            <span class="text-caption font-weight-bold text-medium-emphasis">Select Exam Checkpoint (E1 - E12):</span>
            <div class="d-flex gap-2 overflow-x-auto py-1">
              <v-chip
                v-for="pt in trendExams"
                :key="pt.id"
                :color="activeExamPoint.id === pt.id ? 'primary' : 'surface'"
                :variant="activeExamPoint.id === pt.id ? 'flat' : 'outlined'"
                size="small"
                @click="activeExamPoint = pt"
                class="font-weight-bold cursor-pointer"
              >
                E{{ pt.id }}: {{ pt.avg }}%
              </v-chip>
            </div>
          </div>
        </div>

        <!-- Selected Assessment Checkpoint Detail Card -->
        <div class="pa-5 rounded-2xl border bg-surface d-flex flex-column flex-md-row justify-space-between align-md-center">
          <div class="d-flex align-center mb-3 mb-md-0">
            <v-avatar color="primary" size="52" variant="tonal" class="mr-4 font-weight-black text-h6">
              E{{ activeExamPoint.id }}
            </v-avatar>
            <div>
              <div class="d-flex align-center gap-2">
                <v-chip v-if="activeExamPoint.exam_code" color="secondary" size="x-small" variant="outlined" class="font-mono font-weight-bold ma-5">{{ activeExamPoint.exam_code }}</v-chip>
                <span class="font-weight-black text-h6">{{ activeExamPoint.name }}</span>
                <v-chip color="success" size="x-small" variant="flat" class="font-weight-bold ma-5">{{ activeExamPoint.grade }} Grade</v-chip>
              </div>
              <div class="text-caption text-medium-emphasis mt-0">
                Period: {{ activeExamPoint.period }} &bull; Stream Benchmark: {{ activeExamPoint.stream_avg }}% &bull; Status: {{ activeExamPoint.status }}
              </div>
            </div>
          </div>

          <div class="d-flex align-center gap-4">
            <div class="text-md-right border-l pl-4">
              <div class="text-caption text-medium-emphasis font-weight-bold uppercas">Student Average vs Stream</div>
              <div class="text-h5 font-weight-black text-primary">
                {{ activeExamPoint.avg }}%
                <span class="text-body-2 font-weight-bold text-success ml-1">(+{{ (activeExamPoint.avg - activeExamPoint.stream_avg).toFixed(1) }}% vs stream)</span>
              </div>
            </div>
            <v-spacer/>
          </div>
          <v-btn color="primary" variant="tonal" to="/dashboard/upload-centre" prepend-icon="mdi-file-find-outline" class="rounded-xl font-weight-bold" height="120" >
              View AI OCR Script
            </v-btn>
        </div>
      </v-card>
    </div>

    <!-- PARENT DASHBOARD WORKSPACE -->
    <div v-else-if="activeRole === 'parent'">
      <v-row>
        <v-col cols="12" lg="8">
          <v-card class="pa-6 rounded-3xl border bg-cardBg h-100 shadow-glow" elevation="4">
            <div class="d-flex justify-space-between align-center mb-6">
              <div>
                <h2 class="text-h5 font-weight-black d-flex align-center">
                  <v-icon color="accent" class="mr-2">mdi-home-heart</v-icon>
                  Weekend Home Improvement Steps (ParentReport)
                </h2>
                <p class="text-caption text-medium-emphasis mb-0">Bridging the diagnostic gap with actionable sub-topic practice</p>
              </div>
              <v-btn size="small" color="primary" variant="tonal" to="/dashboard/student-profile-parent">Full Parent Portal</v-btn>
            </div>

            <div v-for="(step, i) in parentActionSteps" :key="i" class="pa-4 rounded-2xl border bg-surface mb-4 d-flex align-center justify-space-between">
              <div class="d-flex align-center">
                <v-checkbox-btn v-model="step.completed" color="success" class="mr-3"></v-checkbox-btn>
                <div>
                  <div class="font-weight-bold text-body-1" :class="step.completed ? 'text-decoration-line-through text-medium-emphasis' : ''">
                    {{ step.title }}
                  </div>
                  <div class="text-caption text-medium-emphasis">{{ step.detail }}</div>
                </div>
              </div>
              <v-chip
                :color="step.priority === 'High Priority' ? 'error' : (step.priority === 'Medium Priority' ? 'warning' : 'success')"
                size="small"
                variant="flat"
                class="font-weight-bold"
              >
                {{ step.priority }}
              </v-chip>
            </div>
          </v-card>
        </v-col>

        <v-col cols="12" lg="4">
          <v-card class="pa-6 rounded-3xl border bg-cardBg h-100 d-flex flex-column justify-space-between shadow-glow" elevation="4">
            <div>
              <h2 class="text-h5 font-weight-black mb-3 d-flex align-center">
                <v-icon color="success" class="mr-2">mdi-whatsapp</v-icon>
                Direct School Link
              </h2>
              <p class="text-subtitle-2 text-medium-emphasis mb-6">
                Connect instantly with your child's TSC subject teachers (John Kamau - Mathematics) to review recent diagnostic findings.
              </p>
              <div class="pa-4 rounded-2xl bg-surface border mb-4">
                <div class="font-weight-bold text-body-2">Mr. John Kamau (Mathematics)</div>
                <div class="text-caption text-medium-emphasis">Office Hours: Mon - Fri, 4:00 PM - 5:30 PM</div>
              </div>
            </div>
            <v-btn color="success" size="large" block prepend-icon="mdi-message-text" elevation="3" class="py-4 font-weight-bold rounded-xl">
              Message Teacher via WhatsApp
            </v-btn>
          </v-card>
        </v-col>
      </v-row>
    </div>

    <!-- TEACHER DASHBOARD WORKSPACE -->
    <div v-else>
      <v-row>
        <v-col cols="12" md="6">
          <v-card class="pa-6 rounded-3xl border bg-cardBg h-100 shadow-glow" elevation="4">
            <div class="d-flex justify-space-between align-center mb-4">
              <h2 class="text-h5 font-weight-black d-flex align-center">
                <v-icon color="secondary" class="mr-2">mdi-chart-bar</v-icon>
                Stream Bottleneck Overview
              </h2>
              <v-chip color="secondary" variant="flat" size="small" class="font-weight-bold">{{ teacherStreamSummary.class_name }}</v-chip>
            </div>
            <div class="mb-6">
              <div class="text-h3 font-weight-black text-secondary">{{ teacherStreamSummary.stream_average }}</div>
              <div class="text-caption text-medium-emphasis">Average stream mastery across {{ teacherStreamSummary.total_students }} enrolled candidates</div>
            </div>
            <div class="pa-4 rounded-2xl bg-surface border mb-6">
              <div class="text-caption font-weight-bold text-error uppercase mb-1">Top Pedagogical Bottleneck (`ClassAnalysis`)</div>
              <div class="font-weight-bold text-body-1">{{ teacherStreamSummary.top_error }}</div>
            </div>
            <v-btn color="secondary" block size="large" to="/dashboard/student-profile-tutor" prepend-icon="mdi-account-group" class="font-weight-bold rounded-xl">
              Open Stream Diagnostics
            </v-btn>
          </v-card>
        </v-col>

        <v-col cols="12" md="6">
          <v-card class="pa-6 rounded-3xl border bg-cardBg h-100 d-flex flex-column justify-space-between shadow-glow" elevation="4">
            <div>
              <div class="d-flex justify-space-between align-center mb-4">
                <h2 class="text-h5 font-weight-black d-flex align-center">
                  <v-icon color="primary" class="mr-2">mdi-file-document-check</v-icon>
                  Marking Rubrics & OCR Queue
                </h2>
                <v-chip color="warning" size="small" variant="flat" class="font-weight-bold">{{ teacherStreamSummary.pending_scans }} Pending Scans</v-chip>
              </div>
              <p class="text-subtitle-2 text-medium-emphasis mb-6">
                Configure your custom marking instructions (TeacherMarkingConfig) in MongoDB so the neural VLM grades student scripts according to your exact curriculum scheme.
              </p>
            </div>
            <div>
              <v-btn color="primary" block size="large" to="/dashboard/upload-centre" prepend-icon="mdi-cog-outline" class="font-weight-bold rounded-xl mb-3">
                Configure Marking Rubric (TeacherMarkingConfig)
              </v-btn>
              <v-btn variant="outlined" color="surface" block to="/admin-dashboard" prepend-icon="mdi-domain" class="font-weight-bold rounded-xl">
                Institutional Admin Oversight
              </v-btn>
            </div>
          </v-card>
        </v-col>
      </v-row>
    </div>

    <!-- MySQL Relational Assessment Table (Shown strictly for Parent and Teacher workspaces) -->
    <v-card v-if="activeRole !== 'student'" class="pa-6 rounded-3xl border bg-cardBg mt-8 shadow-glow" elevation="4">
      <div class="d-flex justify-space-between align-center mb-4">
        <div>
          <h2 class="text-h5 font-weight-black d-flex align-center">
            <v-icon color="primary" class="mr-2">mdi-table-check</v-icon>
            Verified Assessment Records (exam_results MySQL Table)
          </h2>
          <p class="text-caption text-medium-emphasis mb-0">Structured ACID grade sync linked via mongo_analysis_id to detailed vision reports</p>
        </div>
        <v-btn size="small" color="primary" variant="tonal" to="/dashboard/leaderboard">View Academic Leaderboard</v-btn>
      </div>

      <v-table class="bg-transparent">
        <thead>
          <tr>
            <th class="font-weight-bold text-medium-emphasis">Exam / Assessment Name</th>
            <th class="font-weight-bold text-medium-emphasis">Date Recorded</th>
            <th class="font-weight-bold text-medium-emphasis">Score</th>
            <th class="font-weight-bold text-medium-emphasis">Grade</th>
            <th class="font-weight-bold text-medium-emphasis">Verification Status</th>
            <th class="font-weight-bold text-medium-emphasis text-right">Diagnostic Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="exam in recentExams" :key="exam.id">
            <td class="font-weight-bold py-4">
              <div>{{ exam.title }}</div>
              <v-chip v-if="exam.exam_code" size="x-small" color="primary" variant="tonal" class="font-mono mt-1">{{ exam.exam_code }}</v-chip>
            </td>
            <td class="text-medium-emphasis py-4">{{ exam.date }}</td>
            <td class="font-weight-black text-primary py-4">{{ exam.score }} / {{ exam.max_score }}</td>
            <td class="py-4">
              <v-chip :color="exam.grade.startsWith('A') ? 'success' : (exam.grade.startsWith('B') ? 'primary' : 'warning')" size="small" variant="flat" class="font-weight-bold">
                {{ exam.grade }}
              </v-chip>
            </td>
            <td class="py-4">
              <v-chip :color="exam.status === 'Verified' ? 'success' : 'warning'" size="small" variant="tonal" class="font-weight-bold">
                {{ exam.status }}
              </v-chip>
            </td>
            <td class="py-4 text-right">
              <v-btn size="small" variant="tonal" color="accent" to="/dashboard/upload-centre" prepend-icon="mdi-eye">AI Vision Report</v-btn>
            </td>
          </tr>
        </tbody>
      </v-table>
    </v-card>
  </v-container>
</template>

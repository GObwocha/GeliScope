<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()

// Default / active role view switcher ('student' | 'parent' | 'teacher')
const activeRole = ref('student')
const loading = ref(true)

// User Identity State
const userProfile = ref({
  first_name: 'Brian',
  last_name: 'Otieno',
  email: 'brian.otieno@student.geliscope.com',
  role: 'student',
  admission_number: 'ADM/2026/001',
  employee_number: 'TSC/10001',
  school: { name: 'Nairobi Academy Secondary', county: 'Nairobi' },
  class_group: 'Form 2 East'
})

// MySQL Relational Assessment Data (`exam_results`)
const recentExams = ref([
  { id: 101, title: 'Term 2 Mid-Term Mathematics', score: 88, max_score: 100, grade: 'A', date: '2026-07-10', status: 'Verified' },
  { id: 102, title: 'Term 2 Mid-Term Physics', score: 76, max_score: 100, grade: 'A-', date: '2026-07-08', status: 'Verified' },
  { id: 103, title: 'Term 2 Mid-Term Chemistry', score: 62, max_score: 100, grade: 'B', date: '2026-07-05', status: 'Needs Revision' }
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
onMounted(async () => {
  try {
    const savedUser = localStorage.getItem('geliscope_user')
    if (savedUser) {
      const parsed = JSON.parse(savedUser)
      userProfile.value = { ...userProfile.value, ...parsed }
      if (parsed.role) activeRole.value = parsed.role
    } else {
      // Try fetching from MySQL / API
      const res = await axios.get('/api/v1/users?role=student')
      if (res.data && res.data.length > 0) {
        userProfile.value = { ...userProfile.value, ...res.data[0] }
      }
    }
  } catch (err) {
    console.log('Using default local profile state for UserDashboard')
  } finally {
    loading.value = false
  }
})

const switchRole = (role) => {
  activeRole.value = role
  userProfile.value.role = role
  if (role === 'parent') {
    userProfile.value.first_name = 'David'
    userProfile.value.last_name = 'Otieno'
  } else if (role === 'teacher') {
    userProfile.value.first_name = 'John'
    userProfile.value.last_name = 'Kamau'
  } else {
    userProfile.value.first_name = 'Brian'
    userProfile.value.last_name = 'Otieno'
  }
}
</script>

<template>
  <v-container class="py-8">
    <!-- Top Welcome Banner & Role Switcher -->
    <v-card class="pa-6 rounded-3xl border bg-cardBg shadow-glow mb-8" elevation="4">
      <v-row align="center">
        <v-col cols="12" md="7" class="d-flex align-center">
          <v-avatar :color="activeRole === 'student' ? 'primary' : (activeRole === 'teacher' ? 'secondary' : 'accent')" size="76" class="mr-6 font-weight-black text-h4 shadow-glow">
            <span class="text-white">{{ userProfile.first_name[0] }}{{ userProfile.last_name[0] }}</span>
          </v-avatar>
          <div>
            <div class="d-flex align-center gap-2 mb-2">
              <v-chip :color="activeRole === 'student' ? 'primary' : (activeRole === 'teacher' ? 'secondary' : 'accent')" size="small" variant="flat" class="font-weight-black uppercase">
                {{ activeRole }} DASHBOARD
              </v-chip>
              <v-chip color="surface" size="small" variant="outlined" class="font-weight-bold">
                {{ activeRole === 'teacher' ? userProfile.employee_number : userProfile.admission_number }}
              </v-chip>
            </div>
            <h1 class="text-h3 font-weight-black">Welcome back, {{ userProfile.first_name }}!</h1>
            <p class="text-subtitle-1 text-medium-emphasis mb-0 d-flex align-center mt-1">
              <v-icon size="18" color="primary" class="mr-1">mdi-school-outline</v-icon>
              {{ userProfile.school.name }} &bull; {{ activeRole === 'teacher' ? 'TSC Examiner' : userProfile.class_group }}
            </p>
          </div>
        </v-col>

        <v-col cols="12" md="5" class="d-flex flex-column align-md-end justify-center">
          <div class="text-caption font-weight-bold uppercase text-medium-emphasis mb-2">Preview Role Workspace</div>
          <v-btn-toggle
            v-model="activeRole"
            mandatory
            divided
            variant="outlined"
            color="primary"
            class="rounded-xl overflow-hidden bg-surface"
          >
            <v-btn value="student" @click="switchRole('student')" prepend-icon="mdi-school-outline" class="font-weight-bold px-4">Student</v-btn>
            <v-btn value="teacher" @click="switchRole('teacher')" prepend-icon="mdi-presentation-play" class="font-weight-bold px-4">Teacher</v-btn>
            <v-btn value="parent" @click="switchRole('parent')" prepend-icon="mdi-account-heart-outline" class="font-weight-bold px-4">Parent</v-btn>
          </v-btn-toggle>
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
                  <v-icon size="28">mdi-camera-metering</v-icon>
                </v-avatar>
                <v-chip color="primary" size="small" variant="flat" class="font-weight-bold">VISION AI</v-chip>
              </div>
              <h2 class="text-h5 font-weight-black mb-2">Scan & Evaluate Exam Script</h2>
              <p class="text-subtitle-2 text-medium-emphasis mb-6">
                Upload your handwritten paper (`.jpg`, `.pdf`) for instant diagram OCR extraction and question-by-question Bloom's taxonomy breakdown.
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
                <p class="text-caption text-medium-emphasis mb-0">Unstructured AI diagnostics synced from MongoDB (`exam_analyses` collection)</p>
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
                  Weekend Home Improvement Steps (`ParentReport`)
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
                Connect instantly with your child's TSC subject teachers (`John Kamau - Mathematics`) to review recent diagnostic findings.
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
                Configure your custom marking instructions (`TeacherMarkingConfig`) in MongoDB so the neural VLM grades student scripts according to your exact curriculum scheme.
              </p>
            </div>
            <div>
              <v-btn color="primary" block size="large" to="/dashboard/upload-centre" prepend-icon="mdi-cog-outline" class="font-weight-bold rounded-xl mb-3">
                Configure Marking Rubric (`TeacherMarkingConfig`)
              </v-btn>
              <v-btn variant="outlined" color="surface" block to="/admin-dashboard" prepend-icon="mdi-domain" class="font-weight-bold rounded-xl">
                Institutional Admin Oversight
              </v-btn>
            </div>
          </v-card>
        </v-col>
      </v-row>
    </div>

    <!-- MySQL Relational Assessment Table (Shared across all roles) -->
    <v-card class="pa-6 rounded-3xl border bg-cardBg mt-8 shadow-glow" elevation="4">
      <div class="d-flex justify-space-between align-center mb-4">
        <div>
          <h2 class="text-h5 font-weight-black d-flex align-center">
            <v-icon color="primary" class="mr-2">mdi-table-check</v-icon>
            Verified Assessment Records (`exam_results` MySQL Table)
          </h2>
          <p class="text-caption text-medium-emphasis mb-0">Structured ACID grade sync linked via `mongo_analysis_id` to detailed vision reports</p>
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
            <td class="font-weight-bold py-4">{{ exam.title }}</td>
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
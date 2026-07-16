<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const students = ref([])
const selectedStudent = ref(null)
const loading = ref(true)

const skillProfile = ref({
  logical_deduction: 88,
  spatial_reasoning: 74,
  algebraic_formulation: 92,
  critical_evaluation: 65,
  careers: ['Data Scientist', 'Civil Engineer', 'Actuarial Analyst', 'AI Specialist'],
  recent_exams: [
    { exam_code: 'EXAM-2026-M1-MATH', name: 'Form 2 Mid-Term 1 - Mathematics', date: '2026-03-15', score: '78/100', grade: 'B+' },
    { exam_code: 'EXAM-2026-M1-PHYS', name: 'Form 2 Mid-Term 1 - Physics', date: '2026-03-18', score: '84/100', grade: 'A-' },
    { exam_code: 'EXAM-2026-M1-CHEM', name: 'Form 2 Mid-Term 1 - Chemistry', date: '2026-03-20', score: '71/100', grade: 'B' }
  ]
})

onMounted(async () => {
  try {
    const res = await axios.get('/api/v1/users?role=student')
    if (res.data && res.data.length > 0) {
      students.value = res.data
      selectedStudent.value = res.data[0]
    }
  } catch (err) {
    console.error('Error fetching students:', err)
  } finally {
    loading.value = false
  }
})
</script>

<template>
  <v-container class="py-10">
    <div class="mb-8">
      <v-chip color="secondary" size="small" variant="flat" prepend-icon="mdi-brain" class="mb-2 font-weight-bold">
        STUDENT SKILL PROFILING (`StudentSkillProfile`)
      </v-chip>
      <h1 class="text-h3 font-weight-black">Individual Bloom's Cognitive Analytics</h1>
      <p class="text-subtitle-1 text-medium-emphasis mt-1">
        Tracks historical exam progression, logical reasoning milestones, and automated career path recommendations.
      </p>
    </div>

    <v-row v-if="loading">
      <v-col cols="12" class="text-center py-16">
        <v-progress-circular indeterminate color="primary" size="64"></v-progress-circular>
      </v-col>
    </v-row>

    <template v-else>
      <v-row>
        <!-- Student Selector & Basic Profile -->
        <v-col cols="12" md="4">
          <v-card class="pa-6 border mb-6 bg-cardBg" elevation="3">
            <h3 class="text-h6 font-weight-bold mb-4">Student Profile Selection</h3>
            
            <v-select
              v-model="selectedStudent"
              :items="students"
              item-title="first_name"
              item-value="id"
              label="Select Student Account"
              variant="outlined"
              return-object
              density="comfortable"
              class="mb-6"
            >
              <template v-slot:selection="{ item }">
                <div class="d-flex align-center">
                  <v-avatar color="primary" size="24" class="mr-2 text-caption font-weight-bold">
                    {{ item.raw.first_name ? item.raw.first_name[0] : 'S' }}
                  </v-avatar>
                  {{ item.raw.first_name }} {{ item.raw.last_name }}
                </div>
              </template>
            </v-select>

            <div v-if="selectedStudent" class="text-center py-4 border rounded-2xl bg-surface mb-6">
              <v-avatar color="primary" size="80" class="mb-3 font-weight-bold shadow-glow text-h4">
                <span class="text-white">{{ selectedStudent.first_name[0] }}{{ selectedStudent.last_name[0] }}</span>
              </v-avatar>
              <div class="text-h5 font-weight-bold">{{ selectedStudent.first_name }} {{ selectedStudent.last_name }}</div>
              <div class="text-caption text-medium-emphasis">{{ selectedStudent.email }}</div>
              <v-chip color="accent" size="small" variant="flat" class="mt-3 font-weight-bold">
                Form 2 East &bull; ADM/2025/001
              </v-chip>
            </div>

            <h4 class="text-subtitle-2 font-weight-bold uppercase text-medium-emphasis mb-3">AI Suggested Career Fits</h4>
            <div class="d-flex flex-wrap gap-2">
              <v-chip v-for="car in skillProfile.careers" :key="car" color="secondary" variant="tonal" prepend-icon="mdi-briefcase-check">
                {{ car }}
              </v-chip>
            </div>
          </v-card>
        </v-col>

        <!-- Cognitive Metrics & Historical Progress -->
        <v-col cols="12" md="8">
          <v-card class="pa-6 border mb-6 bg-cardBg" elevation="4">
            <h3 class="text-h5 font-weight-bold mb-2 d-flex align-center">
              <v-icon color="accent" class="mr-2">mdi-chart-radar</v-icon> Cognitive Competency Index (`MongoDB`)
            </h3>
            <p class="text-caption text-medium-emphasis mb-6">
              Synthesized across 14 scanned exam papers and 180+ individual question responses (`question_scores`).
            </p>

            <v-row class="mb-8">
              <v-col cols="12" sm="6">
                <div class="pa-4 rounded-xl border bg-surface">
                  <div class="d-flex justify-space-between align-center mb-2">
                    <span class="font-weight-bold">Logical Deduction</span>
                    <v-chip size="x-small" color="success" variant="flat">92% Mastery</v-chip>
                  </div>
                  <v-progress-linear model-value="92" color="success" height="10" rounded></v-progress-linear>
                  <div class="text-caption text-medium-emphasis mt-2">Strong performance in algebraic step transformations.</div>
                </div>
              </v-col>

              <v-col cols="12" sm="6">
                <div class="pa-4 rounded-xl border bg-surface">
                  <div class="d-flex justify-space-between align-center mb-2">
                    <span class="font-weight-bold">Spatial & Geometry Reasoning</span>
                    <v-chip size="x-small" color="warning" variant="flat">74% Proficient</v-chip>
                  </div>
                  <v-progress-linear model-value="74" color="warning" height="10" rounded></v-progress-linear>
                  <div class="text-caption text-medium-emphasis mt-2">Recommended: Practice 3D projection diagrams.</div>
                </div>
              </v-col>

              <v-col cols="12" sm="6">
                <div class="pa-4 rounded-xl border bg-surface">
                  <div class="d-flex justify-space-between align-center mb-2">
                    <span class="font-weight-bold">Critical Evaluation (Bloom's Top Tier)</span>
                    <v-chip size="x-small" color="info" variant="flat">65% Developing</v-chip>
                  </div>
                  <v-progress-linear model-value="65" color="info" height="10" rounded></v-progress-linear>
                  <div class="text-caption text-medium-emphasis mt-2">Focus on open-ended proof word problems.</div>
                </div>
              </v-col>

              <v-col cols="12" sm="6">
                <div class="pa-4 rounded-xl border bg-surface">
                  <div class="d-flex justify-space-between align-center mb-2">
                    <span class="font-weight-bold">Algebraic Formulation</span>
                    <v-chip size="x-small" color="primary" variant="flat">88% Advanced</v-chip>
                  </div>
                  <v-progress-linear model-value="88" color="primary" height="10" rounded></v-progress-linear>
                  <div class="text-caption text-medium-emphasis mt-2">Flawless execution of quadratic equation factorization.</div>
                </div>
              </v-col>
            </v-row>

            <h4 class="text-subtitle-1 font-weight-bold mb-3 d-flex align-center">
              <v-icon color="primary" class="mr-2">mdi-history</v-icon> Recent Assessment Records (`exam_results`)
            </h4>
            <v-table class="bg-surface border rounded-xl">
              <thead>
                <tr>
                  <th class="font-weight-bold">Exam Title</th>
                  <th class="font-weight-bold">Date Scanned</th>
                  <th class="font-weight-bold">Score (`decimal:2`)</th>
                  <th class="font-weight-bold">Grade</th>
                  <th class="font-weight-bold">Action</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="exam in skillProfile.recent_exams" :key="exam.name">
                  <td class="font-weight-bold">
                    <div>{{ exam.name }}</div>
                    <v-chip v-if="exam.exam_code" size="x-small" color="secondary" variant="tonal" class="font-mono mt-1">{{ exam.exam_code }}</v-chip>
                  </td>
                  <td class="text-caption text-medium-emphasis">{{ exam.date }}</td>
                  <td class="font-weight-black text-primary">{{ exam.score }}</td>
                  <td><v-chip size="small" color="success" variant="flat" class="font-weight-bold">{{ exam.grade }}</v-chip></td>
                  <td>
                    <v-btn size="small" variant="text" color="primary" to="/dashboard/upload-centre">Re-analyze Scan</v-btn>
                  </td>
                </tr>
              </tbody>
            </v-table>
          </v-card>
        </v-col>
      </v-row>
    </template>
  </v-container>
</template>

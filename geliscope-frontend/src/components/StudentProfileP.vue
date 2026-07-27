<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const parent = ref(null)
const children = ref([])
const selectedChild = ref(null)
const teachers = ref([])
const loading = ref(true)

const parentReport = ref({
  term: 'Term 1 2026 Evaluation',
  academic_summary: 'Brian has shown remarkable dedication in Sciences and Mathematics. His attendance and test completion rate is 100%. However, detailed analysis shows he struggles when word problems require setting up right-angled triangle ratios.',
  home_improvement_steps: [
    'Ensure Brian sets aside 30 minutes every Tuesday and Thursday evening specifically for Mathematics word problems.',
    'Encourage him to sketch diagrams before attempting algebraic solutions—our OCR scans show he often skips visual diagramming.',
    'Review the attached practice sheet on Angles of Elevation during the upcoming weekend prep.'
  ],
  subtopic_breakdown: [
    { topic: 'Algebraic Factorization', status: 'Mastered', score: '95%', advice: 'Ready for advanced Form 3 equations.' },
    { topic: '3D Geometry & Surface Area', status: 'Mastered', score: '88%', advice: 'Excellent spatial grasp.' },
    { topic: 'Trigonometric Ratios (SOH-CAH-TOA)', status: 'Attention Needed', score: '42%', advice: 'Requires extra practice setting up sine/cosine ratios from word problems.' }
  ]
})

onMounted(async () => {
  try {
    const res = await axios.get('/api/v1/parents/1')
    if (res.data) {
      parent.value = res.data
      if (res.data.students && res.data.students.length > 0) {
        children.value = res.data.students
        selectedChild.value = res.data.students[0]
        
        // Fetch teachers teaching this child
        try {
          const tRes = await axios.get(`/api/v1/parents/1/students/${selectedChild.value.id}/teachers`)
          if (tRes.data) teachers.value = tRes.data
        } catch (e) {
          teachers.value = [
            { id: 1, specialization: 'Mathematics', employee_number: 'TSC/10001', user: { first_name: 'John', last_name: 'Kamau', email: 'john.kamau@teacher.geliscope.com' } },
            { id: 2, specialization: 'English & Literature', employee_number: 'TSC/10002', user: { first_name: 'Grace', last_name: 'Mutua', email: 'grace.mutua@teacher.geliscope.com' } }
          ]
        }
      }
    }
  } catch (err) {
    console.error('Error fetching parent profile:', err)
  } finally {
    loading.value = false
  }
})
</script>

<template>
  <v-container class="py-10">
    <div class="mb-8">
      <v-chip color="primary" size="small" variant="flat" prepend-icon="mdi-account-heart" class="mb-2 font-weight-bold">
        GELISCOPE PARENTAL DIAGNOSTIC PORTAL
      </v-chip>
      <h1 class="text-h3 font-weight-black">Parent Terminal & Home Improvement Guide</h1>
      <p class="text-subtitle-1 text-medium-emphasis mt-1">
        No more guessing what "Math: C" means. Access detailed sub-topic breakdowns, direct teacher contacts, and AI-prescribed home revision steps.
      </p>
    </div>

    <v-row v-if="loading">
      <v-col cols="12" class="text-center py-16">
        <v-progress-circular indeterminate color="primary" size="64"></v-progress-circular>
      </v-col>
    </v-row>

    <template v-else>
      <v-row>
        <!-- Left: Linked Children Roster & Teacher Contacts -->
        <v-col cols="12" md="4">
          <v-card class="pa-6 border mb-6 bg-surface" elevation="3">
            <h3 class="text-h6 font-weight-bold mb-4 d-flex align-center">
              <v-icon color="accent" class="mr-2">mdi-account-child</v-icon> Linked Children Roster
            </h3>

            <v-select
              v-model="selectedChild"
              :items="children"
              item-title="user.first_name"
              item-value="id"
              label="Select Enrolled Child"
              variant="outlined"
              return-object
              density="comfortable"
              class="mb-6"
            >
              <template v-slot:selection="{ item }">
                <div class="d-flex align-center font-weight-bold">
                  <v-avatar color="accent" size="24" class="mr-2 text-caption">
                    {{ item.raw.user ? item.raw.user.first_name[0] : 'C' }}
                  </v-avatar>
                  {{ item.raw.user ? `${item.raw.user.first_name} ${item.raw.user.last_name}` : `Child #${item.raw.id}` }}
                </div>
              </template>
            </v-select>

            <div v-if="selectedChild" class="pa-4 border rounded-xl bg-surface mb-6">
              <div class="d-flex align-center mb-3">
                <v-avatar color="accent" size="48" class="mr-3 font-weight-bold text-h6">
                  <span class="text-white">{{ selectedChild.user ? selectedChild.user.first_name[0] : 'C' }}</span>
                </v-avatar>
                <div>
                  <div class="font-weight-bold text-subtitle-1">{{ selectedChild.user ? `${selectedChild.user.first_name} ${selectedChild.user.last_name}` : `Child #${selectedChild.id}` }}</div>
                  <v-chip size="x-small" color="primary" variant="outlined" class="font-mono mt-1">{{ selectedChild.admission_number }}</v-chip>
                </div>
              </div>
              <v-divider class="my-3"></v-divider>
              <div class="d-flex justify-space-between text-caption">
                <span class="text-medium-emphasis">Current Stream:</span>
                <span class="font-weight-bold">Form {{ selectedChild.current_class_id || 1 }} East</span>
              </div>
            </div>

            <!-- Subject Teachers Roster -->
            <h4 class="text-subtitle-2 font-weight-bold uppercase text-medium-emphasis mb-3 d-flex align-center">
              <v-icon color="secondary" size="16" class="mr-2">mdi-human-male-board</v-icon> Child's Subject Teachers (`/teachers`)
            </h4>
            <v-list class="bg-surface rounded-xl border pa-0">
              <v-list-item v-for="tch in teachers" :key="tch.id" class="pa-3 border-b">
                <template v-slot:prepend>
                  <v-avatar color="secondary" variant="tonal" size="36" class="mr-3">
                    <v-icon size="18">mdi-account-tie</v-icon>
                  </v-avatar>
                </template>
                <v-list-item-title class="font-weight-bold text-body-2">
                  {{ tch.user ? `${tch.user.first_name} ${tch.user.last_name}` : `Teacher #${tch.id}` }}
                </v-list-item-title>
                <v-list-item-subtitle class="text-caption text-secondary font-weight-medium">
                  {{ tch.specialization || 'General Subject' }} &bull; {{ tch.employee_number }}
                </v-list-item-subtitle>
                <template v-slot:append>
                  <v-btn icon size="small" variant="text" color="primary" @click="alert(`Direct messaging Mr/Ms ${tch.user ? tch.user.last_name : tch.id} via Parent Portal`)">
                    <v-icon>mdi-message-text-outline</v-icon>
                  </v-btn>
                </template>
              </v-list-item>
            </v-list>
          </v-card>
        </v-col>

        <!-- Right: Termly Report & Actionable Home Steps -->
        <v-col cols="12" md="8">
          <v-card class="pa-6 border mb-6 bg-surface" elevation="4">
            <div class="d-flex justify-space-between align-start mb-6">
              <div>
                <v-chip color="warning" size="small" variant="flat" prepend-icon="mdi-file-document-check" class="mb-2 font-weight-bold">
                  MONGODB PARENT REPORT
                </v-chip>
                <h3 class="text-h5 font-weight-bold">{{ parentReport.term }}</h3>
                <p class="text-caption text-medium-emphasis">Comprehensive diagnostic guidance synced from GeliScope Vision Engine</p>
              </div>
              <v-btn color="primary" variant="outlined" prepend-icon="mdi-download" size="small">
                Download PDF Report
              </v-btn>
            </div>

            <v-alert color="primary" variant="tonal" icon="mdi-account-check" border="start" class="mb-6">
              <div class="font-weight-bold mb-1">Academic Summary for Parents</div>
              <div class="text-body-2">{{ parentReport.academic_summary }}</div>
            </v-alert>

            <!-- Subtopic Breakdown Table -->
            <h4 class="text-subtitle-1 font-weight-bold mb-3 d-flex align-center">
              <v-icon color="accent" class="mr-2">mdi-format-list-checks</v-icon> Exact Sub-Topic Status (Where Your Child Stands)
            </h4>
            <v-table class="bg-surface border rounded-xl mb-6">
              <thead>
                <tr>
                  <th class="font-weight-bold">Sub-Topic Area</th>
                  <th class="font-weight-bold">Current Score</th>
                  <th class="font-weight-bold">Status</th>
                  <th class="font-weight-bold">AI Parental Guidance</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="sub in parentReport.subtopic_breakdown" :key="sub.topic">
                  <td class="font-weight-bold">{{ sub.topic }}</td>
                  <td class="font-weight-black text-primary">{{ sub.score }}</td>
                  <td>
                    <v-chip size="x-small" :color="sub.status === 'Mastered' ? 'success' : 'error'" variant="flat" class="font-weight-bold">
                      {{ sub.status }}
                    </v-chip>
                  </td>
                  <td class="text-caption text-medium-emphasis">{{ sub.advice }}</td>
                </tr>
              </tbody>
            </v-table>

            <!-- Actionable Home Revision Steps -->
            <h4 class="text-subtitle-1 font-weight-bold mb-3 d-flex align-center">
              <v-icon color="warning" class="mr-2">mdi-home-analytics</v-icon> Concrete Home Improvement Steps (`ParentReport->improvement_steps`)
            </h4>
            <v-list class="bg-surface rounded-xl border pa-0">
              <v-list-item v-for="(step, idx) in parentReport.home_improvement_steps" :key="idx" class="pa-4 border-b">
                <template v-slot:prepend>
                  <v-avatar color="warning" variant="flat" size="32" class="mr-4 text-body-2 font-weight-black text-white">
                    {{ idx + 1 }}
                  </v-avatar>
                </template>
                <v-list-item-title class="text-body-2 font-weight-medium leading-relaxed">{{ step }}</v-list-item-title>
              </v-list-item>
            </v-list>
          </v-card>
        </v-col>
      </v-row>
    </template>
  </v-container>
</template>

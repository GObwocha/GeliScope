<script setup>
const steps = [
  {
    number: '01',
    title: 'Scan or Upload Student Exam Scripts',
    description: 'Teachers or students capture photos of handwritten exam papers using a smartphone camera or scanner. The Vision OCR engine extracts mathematical formulas, drawings, and text precisely.',
    icon: 'mdi-camera-plus',
    color: 'primary'
  },
  {
    number: '02',
    title: 'Automated Rubric & Bloom\'s Mapping',
    description: 'GeliScope matches answers against the teacher-prescribed marking configuration (`TeacherMarkingConfig`) and classifies cognitive skills from Remembering up to Evaluating.',
    icon: 'mdi-cogs',
    color: 'secondary'
  },
  {
    number: '03',
    title: 'Dual-Database Persistence',
    description: 'Numerical question scores and rankings (`question_scores`, `leaderboard_entries`) are atomically saved to MySQL, while deep unstructured insights are saved to MongoDB (`ExamAnalysis`).',
    icon: 'mdi-database-sync',
    color: 'accent'
  },
  {
    number: '04',
    title: 'Instant Actionable Guidance for Parents & Students',
    description: 'Parents receive clear home improvement steps (`ParentReport`), while students access interactive step-by-step revision suggestions right in their personal portal.',
    icon: 'mdi-account-group-outline',
    color: 'warning'
  }
]
</script>

<template>
  <v-container class="py-16">
    <div class="text-center max-w-3xl mx-auto mb-16">
      <v-chip color="primary" size="small" variant="flat" prepend-icon="mdi-help-circle" class="mb-3 font-weight-bold">
        END-TO-END WORKFLOW
      </v-chip>
      <h1 class="text-h2 font-weight-black tracking-tight mb-4">How GeliScope Works</h1>
      <p class="text-h6 text-medium-emphasis font-weight-light">
        A seamless 4-step pipeline designed specifically for Kenyan secondary schools (`Form 1 - Form 4`) to eliminate grading friction and empower parents.
      </p>
    </div>

    <v-row>
      <v-col cols="12" md="6" lg="3" v-for="step in steps" :key="step.number">
        <v-card class="pa-6 border h-100 bg-cardBg d-flex flex-column justify-space-between relative overflow-hidden group hover:border-primary transition" elevation="4">
          <div>
            <div class="d-flex justify-space-between align-center mb-6">
              <v-avatar :color="step.color" variant="tonal" size="56">
                <v-icon size="28">{{ step.icon }}</v-icon>
              </v-avatar>
              <span class="text-h3 font-weight-black opacity-20 font-mono">{{ step.number }}</span>
            </div>
            <h3 class="text-h5 font-weight-bold mb-3">{{ step.title }}</h3>
            <p class="text-body-2 text-medium-emphasis leading-relaxed">{{ step.description }}</p>
          </div>
          <div class="mt-6 pt-4 border-t d-flex align-center text-caption text-primary font-weight-bold">
            Learn more about Step {{ step.number }} <v-icon size="16" class="ml-1">mdi-arrow-right</v-icon>
          </div>
        </v-card>
      </v-col>
    </v-row>

    <div class="mt-16 text-center">
      <v-card class="pa-10 border bg-gradient-to-r from-indigo-950/60 to-purple-950/60 rounded-3xl" elevation="6">
        <h2 class="text-h3 font-weight-black mb-4">Ready to test the Vision OCR Pipeline?</h2>
        <p class="text-h6 text-medium-emphasis font-weight-light mb-8 max-w-2xl mx-auto">
          Upload a sample Form 2 Mathematics paper right now and watch our dual-database engine grade and map each question in real-time.
        </p>
        <v-btn color="primary" size="x-large" to="/dashboard/upload-centre" prepend-icon="mdi-camera" elevation="6" class="px-10 py-4 font-weight-bold">
          Try the Upload Centre Now
        </v-btn>
      </v-card>
    </div>
  </v-container>
</template>

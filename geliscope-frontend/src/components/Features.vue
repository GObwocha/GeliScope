<script setup>
const features = [
  {
    title: 'Vision OCR & Diagram Parsing',
    description: 'Advanced neural networks built to accurately transcribe Kenyan handwritten exam papers, geometry drawings, and multi-step algebraic working without human intervention.',
    badge: 'Vision Engine',
    icon: 'mdi-eye-check-outline',
    color: 'primary'
  },
  {
    title: 'Bloom\'s Taxonomy Evaluation Matrix',
    description: 'Automatically classifies student errors across 5 distinct cognitive levels (`Remembering`, `Understanding`, `Applying`, `Analyzing`, `Evaluating`) to reveal root causes.',
    badge: 'Cognitive AI',
    icon: 'mdi-brain',
    color: 'secondary'
  },
  {
    title: 'Dual-Database Architecture Sync',
    description: 'Relational MySQL (`geliscope`) guarantees ACID compliance for student grades and TSC teacher rosters, while MongoDB (`mongodb/laravel-mongodb`) stores high-volume AI telemetry.',
    badge: 'Hybrid Storage',
    icon: 'mdi-database-check-outline',
    color: 'accent'
  },
  {
    title: 'Actionable Home Improvement Steps for Parents',
    description: 'Translates technical subject curricula into simple, concrete weekend routines (`ParentReport->improvement_steps`) so parents can actively tutor their children.',
    badge: 'Parent Portal',
    icon: 'mdi-home-heart',
    color: 'warning'
  },
  {
    title: 'Automated Teacher Marking Rubric (`MarkingConfig`)',
    description: 'Subject teachers define exact required keywords, partial credit thresholds, and question weights once, and GeliScope grades entire 50-student streams consistently.',
    badge: 'Teacher Tool',
    icon: 'mdi-file-document-edit-outline',
    color: 'info'
  },
  {
    title: 'Academic Leaderboard & Career Alignment',
    description: 'Real-time subject ranking indices combined with longitudinal skill profiling (`StudentSkillProfile`) to recommend tailored university and STEM career trajectories.',
    badge: 'Analytics',
    icon: 'mdi-trophy-award',
    color: 'success'
  }
]
</script>

<template>
  <v-container class="py-16">
    <div class="text-center max-w-3xl mx-auto mb-16">
      <v-chip color="secondary" size="small" variant="flat" prepend-icon="mdi-sparkles" class="mb-3 font-weight-bold">
        CORE TECHNOLOGIES & SOLUTIONS
      </v-chip>
      <h1 class="text-h2 font-weight-black tracking-tight mb-4">GeliScope Features Engine</h1>
      <p class="text-h6 text-medium-emphasis font-weight-light">
        Every feature is engineered to directly resolve the exact EdTech pain points identified under **SDG Problem 4 (Quality Education)**.
      </p>
    </div>

    <v-row>
      <v-col cols="12" md="6" lg="4" v-for="feat in features" :key="feat.title">
        <v-card class="pa-6 border h-100 bg-cardBg d-flex flex-column justify-space-between group hover:border-accent transition" elevation="3">
          <div>
            <div class="d-flex justify-space-between align-center mb-4">
              <v-avatar :color="feat.color" variant="tonal" size="52">
                <v-icon size="26">{{ feat.icon }}</v-icon>
              </v-avatar>
              <v-chip size="x-small" :color="feat.color" variant="flat" class="font-weight-bold uppercase">{{ feat.badge }}</v-chip>
            </div>
            <h3 class="text-h5 font-weight-bold mb-3">{{ feat.title }}</h3>
            <p class="text-body-2 text-medium-emphasis leading-relaxed">{{ feat.description }}</p>
          </div>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script setup>
const features = [
 {
 title: 'Vision OCR & Diagram Parsing',
 description: 'Advanced neural networks built to accurately transcribe Kenyan handwritten exam papers, geometry drawings, and multi-step algebraic working without human intervention.',
 badge: 'Vision Engine',
 icon: 'mdi-eye-check-outline',
 color: '#AB7DE3', // primary
 cols: 12,
 md: 8
 },
 {
 title: 'Cognitive AI Matrix',
 description: 'Classifies errors across 5 Bloom\'s cognitive levels.',
 badge: 'Taxonomy',
 icon: 'mdi-brain',
 color: '#51FAAA', // secondary
 cols: 12,
 md: 4
 },
 {
 title: 'Dual-Database Architecture Sync',
 description: 'Relational MySQL guarantees ACID compliance for student grades, while MongoDB stores high-volume unstructured AI telemetry.',
 badge: 'Hybrid Storage',
 icon: 'mdi-database-check-outline',
 color: '#FF81FF', // accent
 cols: 12,
 md: 4
 },
 {
 title: 'Actionable Steps for Parents',
 description: 'Translates technical subject curricula into simple, concrete weekend routines so parents can actively tutor their children at home.',
 badge: 'Parent Portal',
 icon: 'mdi-home-heart',
 color: '#AB7DE3', // primary
 cols: 12,
 md: 4
 },
 {
 title: 'Automated Teacher Marking Rubric',
 description: 'Teachers define required keywords and partial credit thresholds once. GeliScope grades entire streams consistently and instantly.',
 badge: 'Teacher Tool',
 icon: 'mdi-file-document-edit-outline',
 color: '#51FAAA', // secondary
 cols: 12,
 md: 4
 },
 {
 title: 'Academic Leaderboard & Career Alignment',
 description: 'Real-time subject ranking indices combined with longitudinal skill profiling to recommend tailored university and STEM career trajectories directly mapping to student strengths.',
 badge: 'Analytics',
 icon: 'mdi-trophy-award',
 color: '#FF81FF', // accent
 cols: 12,
 md: 12
 }
]
</script>

<template>
 <div class="features-page position-relative w-100 overflow-hidden pb-16">
 
 
 <v-container class="pt-16 mt-8 position-relative z-10">
 <div class="text-center max-w-3xl mx-auto mb-16">
 <v-chip color="primary" variant="outlined" class="mb-6 font-weight-bold border-opacity-50 e 125, 227, 0.05);">
 Core Technologies & Solutions
 </v-chip>
 <h1 class="text-h2 font-weight-black tracking-tight mb-4 ">
 <span class="text-gradient">GeliScope</span> Features Engine
 </h1>
 <p class="text-h6 text-muted font-weight-light">
 Every feature is engineered to directly resolve the exact EdTech pain points identified under SDG Problem 4 (Quality Education).
 </p>
 </div>

 <v-row class="bento-grid">
 <v-col v-for="(feat, i) in features" :key="i" :cols="feat.cols" :md="feat.md">
 <div class="bento-card h-100 p-8 rounded-xl pa-8 d-flex flex-column justify-space-between">
 <div>
 <div class="d-flex justify-space-between align-center mb-6">
 <div class="rounded-circle d-flex align-center justify-center shadow-glow" :style="`width: 50px; height: 50px; background: ${feat.color}20; border: 1px solid ${feat.color}40;`">
 <v-icon :color="feat.color" size="24">{{ feat.icon }}</v-icon>
 </div>
 <v-chip size="x-small" variant="flat" class="font-weight-bold uppercase" :style="`background: ${feat.color}20; color: ${feat.color};`">{{ feat.badge }}</v-chip>
 </div>
 <h3 class="text-h5 font-weight-bold mb-3">{{ feat.title }}</h3>
 <p class="text-body-1 text-muted leading-relaxed">{{ feat.description }}</p>
 </div>
 
 <!-- Optional decoration for larger cards -->
 <div v-if="feat.md === 8" class="mt-8 d-flex gap-2">
 <div class="bg-primary rounded-pill h-2 w-25"></div>
 <div class="bg-secondary rounded-pill h-2 w-50"></div>
 <div class="bg-accent rounded-pill h-2 w-25"></div>
 </div>
 </div>
 </v-col>
 </v-row>
 </v-container>
 </div>
</template>

<style scoped>
.extracted-style-1 {
 top: -100px; left: 50%; transform: translateX(-50%); opacity: 0.4;
}

.extracted-style-2 {
 border-radius: 100px; background: rgba(171, 125, 227, 0.05);
}

.max-w-3xl { max-width: 800px; }
.z-10 { z-index: 10; }
.h-2 { height: 8px; }
.w-25 { width: 25%; }
.w-50 { width: 50%; }
</style>

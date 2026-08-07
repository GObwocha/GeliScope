<script setup>
const steps = [
 {
 number: '01',
 title: 'Scan or Upload Student Exam Scripts',
 description: 'Teachers or students capture photos of handwritten exam papers using a smartphone camera or scanner. The Vision OCR engine extracts mathematical formulas, drawings, and text precisely.',
 icon: 'mdi-camera-plus',
 color: '#AB7DE3' // Purple
 },
 {
 number: '02',
 title: 'Automated Rubric & Bloom\'s Mapping',
 description: 'GeliScope matches answers against the teacher-prescribed marking configuration and classifies cognitive skills from Remembering up to Evaluating.',
 icon: 'mdi-cogs',
 color: '#51FAAA' // Green
 },
 {
 number: '03',
 title: 'Dual-Database Persistence',
 description: 'Numerical question scores and rankings are atomically saved to MySQL, while deep unstructured insights are saved to MongoDB.',
 icon: 'mdi-database-sync',
 color: '#FF81FF' // Light Purple
 },
 {
 number: '04',
 title: 'Instant Actionable Guidance',
 description: 'Parents receive clear home improvement steps, while students access interactive step-by-step revision suggestions right in their personal portal.',
 icon: 'mdi-account-group-outline',
 color: '#51FAAA' // Green
 }
]
</script>

<template>
 <div class="how-it-works-page position-relative w-100 overflow-hidden pb-16">
 

 <v-container class="pt-16 mt-8 position-relative z-10">
 <div class="text-center max-w-3xl mx-auto mb-16">
 <v-chip color="primary" variant="outlined" class="mb-6 font-weight-bold border-opacity-50 e 125, 227, 0.05);">
 End-to-End Workflow
 </v-chip>
 <h1 class="text-h2 font-weight-black tracking-tight mb-4 ">
 How <span class="text-gradient">GeliScope</span> Works
 </h1>
 <p class="text-h6 text-muted font-weight-light">
 A seamless 4-step pipeline designed specifically for Kenyan secondary schools to eliminate grading friction and empower parents.
 </p>
 </div>

 <!-- Vertical Timeline flow -->
 <v-row class="position-relative">
 <div class="timeline-line hidden-sm-and-down"></div>
 <v-col cols="12" md="6" v-for="(step, index) in steps" :key="step.number" :class="index % 2 === 0 ? 'pr-md-16' : 'pl-md-16 offset-md-6'">
 <div class="bento-card p-8 rounded-xl pa-8 d-flex flex-column justify-space-between mb-8 position-relative">
 
 <!-- Timeline Dot -->
 <div class="timeline-dot hidden-sm-and-down" :style="`background: ${step.color};`" :class="index % 2 === 0 ? 'dot-right' : 'dot-left'"></div>

 <div>
 <div class="d-flex justify-space-between align-center mb-6">
 <div class="rounded-circle d-flex align-center justify-center shadow-glow" :style="`width: 50px; height: 50px; background: ${step.color}20; border: 1px solid ${step.color}40;`">
 <v-icon :color="step.color" size="24">{{ step.icon }}</v-icon>
 </div>
 <span class="text-h4 font-weight-black" :style="`color: ${step.color}; opacity: 0.2; font-family: 'Outfit', monospace;`">{{ step.number }}</span>
 </div>
 <h3 class="text-h5 font-weight-bold mb-3">{{ step.title }}</h3>
 <p class="text-body-1 text-muted leading-relaxed">{{ step.description }}</p>
 </div>
 
 <div class="mt-6 pt-4 d-flex align-center text-caption font-weight-bold" :style="`color: ${step.color}; border-top: 1px solid rgba(255,255,255,0.05);`">
 Learn more about Step {{ step.number }} <v-icon size="16" class="ml-1">mdi-arrow-right</v-icon>
 </div>
 </div>
 </v-col>
 </v-row>

 <div class="mt-16 text-center">
 <div class="bento-card pa-12 rounded-xl position-relative overflow-hidden e, 125, 227, 0.4);">
 <div class="glow-blur-purple e%); opacity: 0.5;"></div>
 
 <h2 class="text-h3 font-weight-black mb-4 position-relative z-10">Ready to test the Vision OCR Pipeline?</h2>
 <p class="text-h6 text-muted font-weight-light mb-8 max-w-2xl mx-auto position-relative z-10">
 Upload a sample Mathematics paper right now and watch our dual-database engine grade and map each question in real-time.
 </p>
 <v-btn color="secondary" size="x-large" to="/dashboard/upload-centre" prepend-icon="mdi-camera" elevation="6" class="pill-btn px-10 py-4 font-weight-bold position-relative z-10">
 Try the Upload Centre Now
 </v-btn>
 </div>
 </div>
 </v-container>
 </div>
</template>

<style scoped>
.extracted-style-1 {
 top: 20%; left: 10%; transform: translateX(-50%); opacity: 0.3;
}

.extracted-style-2 {
 top: 60%; right: 0%; transform: translateX(50%); opacity: 0.3;
}

.extracted-style-3 {
 border-radius: 100px; background: rgba(171, 125, 227, 0.05);
}

.extracted-style-4 {
 border-color: rgba(171, 125, 227, 0.4);
}

.extracted-style-5 {
 top: 50%; left: 50%; transform: translate(-50%, -50%); opacity: 0.5;
}

.max-w-3xl { max-width: 800px; }
.max-w-2xl { max-width: 600px; }
.z-10 { z-index: 10; }

.timeline-line {
 position: absolute;
 top: 20px;
 bottom: 20px;
 left: 50%;
 width: 2px;
 background: linear-gradient(to bottom, rgba(171, 125, 227, 0.1), rgba(81, 250, 170, 0.5), rgba(255, 129, 255, 0.1));
 transform: translateX(-50%);
 z-index: 1;
}

.timeline-dot {
 position: absolute;
 top: 40px;
 width: 16px;
 height: 16px;
 border-radius: 50%;
 box-shadow: 0 0 10px rgba(255,255,255,0.5);
 z-index: 2;
}

.dot-right {
 right: -64px; /* Adjust based on padding/margins */
}

.dot-left {
 left: -64px;
}
</style>

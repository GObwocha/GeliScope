<script setup>
import { ref } from 'vue'

const features = ref([
  {
    title: "Seamless Educational Integrations",
    description: "Plug GeliScope directly into existing learning management systems, Student Information Portals, and digital classroom workspaces effortlessly.",
    icon: "mdi-lightning-bolt",
    type: "integration"
  },
  {
    title: "AI-Powered Socratic Dialogues",
    description: "Rather than just dispensing answers, our conversational engine guides students through deep critical thinking with personalized Socratic steps.",
    type: "socratic"
  },
  {
    title: "Global Multi-Language Support",
    description: "Communicate seamlessly across 95+ dialects in real-time, bridging the gap for bilingual students and international classrooms.",
    type: "translation"
  },
  {
    title: "Parental Insights Radar",
    description: "Deliver instant, digestible summaries directly to parents, ensuring they stay informed without being overwhelmed by data.",
    type: "radar"
  }
])
</script>

<template>
  <v-container class="py-16 max-w-7xl mx-auto position-relative" id="features">
    <!-- Section Headline -->
    <div class="d-flex flex-column flex-md-row align-md-end justify-space-between mb-16 gap-6">
      <div>
        <span class="text-caption text-uppercase tracking-widest text-secondary font-weight-bold d-block mb-2">Omniscient Platform</span>
        <h2 class="text-h3 text-sm-h2 font-weight-black text-white tracking-tight">
          Everything You Need to <br class="d-none d-sm-inline"/> Elevate Education
        </h2>
      </div>
      <p class="text-medium-emphasis text-body-2 text-sm-body-1 max-w-md">
        From tailored AI learning companions to real-time parental insight radars and autonomous teacher curriculum workflows.
      </p>
    </div>

    <!-- Bento Grid Layout -->
    <v-row>
      <v-col cols="12" md="6" v-for="(feature, index) in features" :key="index">
        <v-card class="glass-panel border rounded-xl pa-8 h-100 d-flex flex-column justify-space-between transition-all group" elevation="0">
          
          <div class="h-52 w-100 rounded-lg surface-panel d-flex align-center justify-center position-relative overflow-hidden mb-8">
            <!-- Custom visualization based on feature type -->
            <template v-if="feature.type === 'integration'">
              <div class="glow-sphere rounded-pill transition-transform"></div>
              <div class="position-relative z-10 d-flex align-center gap-6 text-medium-emphasis">
                <v-chip variant="outlined" class="text-caption font-mono" color="white">Canvas LMS</v-chip>
                <div class="icon-box d-flex align-center justify-center p-0.5 shadow-xl">
                  <div class="icon-inner d-flex align-center justify-center text-secondary">
                    <v-icon size="x-large">{{ feature.icon }}</v-icon>
                  </div>
                </div>
                <v-chip variant="outlined" class="text-caption font-mono" color="white">Google Clrm</v-chip>
              </div>
            </template>

            <template v-else-if="feature.type === 'socratic'">
              <div class="w-100 max-w-sm px-6">
                <div class="pill-bar rounded-pill border d-flex align-center px-4 gap-3 mb-3">
                  <div class="dot bg-error"></div>
                  <span class="text-caption text-white font-mono opacity-80">AI Tutor Voice Active • Math</span>
                </div>
                <div class="pill-bar-sm mx-auto rounded-pill border d-flex align-center px-4">
                  <div class="pulse-bar rounded-pill"></div>
                </div>
              </div>
            </template>

            <template v-else-if="feature.type === 'translation'">
              <div class="w-100 mx-6 rounded-lg border-white p-4">
                <div class="d-flex align-center gap-2 text-caption text-accent font-mono mb-2">
                  <div class="dot bg-success"></div>
                  <span>Real-time Translation • 95+ Dialects</span>
                </div>
                <div class="w-100 h-2 bg-white-10 rounded mb-2"></div>
                <div class="w-66 h-2 bg-white-10 rounded mb-4"></div>
                <div class="d-flex gap-2">
                  <v-chip size="x-small" color="secondary" variant="outlined" class="font-mono">Spanish</v-chip>
                  <v-chip size="x-small" color="primary" variant="outlined" class="font-mono">Mandarin</v-chip>
                  <v-chip size="x-small" color="white" variant="outlined" class="font-mono opacity-60">German</v-chip>
                </div>
              </div>
            </template>

            <template v-else>
               <div class="glow-sphere-green rounded-pill transition-transform"></div>
               <v-icon color="secondary" size="100" class="opacity-50">mdi-radar</v-icon>
            </template>
          </div>

          <div>
            <h3 class="text-h5 font-weight-bold text-white mb-2 font-heading">{{ feature.title }}</h3>
            <p class="text-body-2 text-medium-emphasis lh-relaxed">
              {{ feature.description }}
            </p>
          </div>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<style scoped>
.max-w-7xl { max-width: 1280px; }
.max-w-md { max-width: 448px; }
.max-w-sm { max-width: 384px; }
.h-52 { height: 208px; }
.w-66 { width: 66%; }
.gap-2 { gap: 8px; }
.gap-3 { gap: 12px; }
.gap-6 { gap: 24px; }
.lh-relaxed { line-height: 1.625; }
.tracking-widest { letter-spacing: 0.1em; }
.tracking-tight { letter-spacing: -0.025em; }

.glass-panel {
  background: linear-gradient(135deg, rgba(31, 41, 55, 0.6) 0%, rgba(17, 24, 39, 0.8) 100%) !important;
  backdrop-filter: blur(16px);
  border: 1px solid rgba(255, 255, 255, 0.05) !important;
}
.glass-panel:hover {
  border-color: rgba(139, 92, 246, 0.4) !important;
}

.surface-panel {
  background-color: #0d1424;
  border: 1px solid rgba(255, 255, 255, 0.05);
}
.border-white {
  background: rgba(255,255,255,0.05);
  border: 1px solid rgba(255,255,255,0.1);
}
.bg-white-10 {
  background-color: rgba(255,255,255,0.1);
}

.glow-sphere {
  position: absolute;
  width: 144px; height: 144px;
  background: rgba(139, 92, 246, 0.3);
  filter: blur(24px);
}
.glow-sphere-green {
  position: absolute;
  width: 144px; height: 144px;
  background: rgba(34, 211, 238, 0.3);
  filter: blur(24px);
}
.group:hover .glow-sphere {
  transform: scale(1.25);
}

.icon-box {
  width: 56px; height: 56px;
  border-radius: 16px;
  background: linear-gradient(to top right, #8B5CF6, #22D3EE);
  padding: 2px;
  box-shadow: 0 20px 25px -5px rgba(34, 211, 238, 0.2);
}
.icon-inner {
  width: 100%; height: 100%;
  background-color: #0d1424;
  border-radius: 14px;
}

.pill-bar {
  height: 40px; width: 100%;
  background: linear-gradient(to right, rgba(139, 92, 246, 0.4), rgba(34, 211, 238, 0.4));
  border: 1px solid rgba(255,255,255,0.1);
}
.pill-bar-sm {
  height: 32px; width: 80%;
  background: rgba(255,255,255,0.05);
  border: 1px solid rgba(255,255,255,0.05);
}
.dot {
  width: 12px; height: 12px;
  border-radius: 50%;
}
.pulse-bar {
  height: 6px; width: 33%;
  background-color: rgba(34, 211, 238, 0.6);
  animation: pulse 2s infinite;
}
@keyframes pulse {
  0%, 100% { opacity: 1; }
  50% { opacity: .5; }
}
</style>

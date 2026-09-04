<script setup>
import { ref } from 'vue'

const testimonials = ref([
  {
    quote: "GeliScope feels like having an MIT professor sitting next to me at 2 AM. It doesn't just give answers; it actually helps me grasp why formulas work.",
    author: "Sophia Kwan",
    title: "AP Physics Student",
    avatarText: "SK",
    color: "secondary"
  },
  {
    quote: "The Parent Radar is extraordinary. I receive concise weekly digests showing where my daughter excels and where she feels frustrated before report cards come.",
    author: "Marcus Ramirez",
    title: "Father of Two, Austin TX",
    avatarText: "MR",
    color: "primary"
  },
  {
    quote: "Autonomous rubric generation and automated essay diagnostics save me 12 hours every week. I can finally focus on 1-on-1 human mentorship in my classroom.",
    author: "Dr. Elena Hayes",
    title: "High School STEM Lead",
    avatarText: "EH",
    color: "info"
  }
])
</script>

<template>
  <v-container class="py-16 max-w-7xl mx-auto border-t" id="testimonials">
    <div class="text-center max-w-2xl mx-auto mb-16">
      <span class="text-caption text-uppercase font-weight-bold text-accent d-block mb-2 letter-spacing-wide">
        Proven Results
      </span>
      <h2 class="text-h3 font-weight-bold text-white">
        Trusted by Educators & Learners Worldwide
      </h2>
    </div>

    <v-row>
      <v-col 
        cols="12" 
        md="4" 
        v-for="(testimonial, index) in testimonials" 
        :key="index"
      >
        <v-card class="glass-panel border rounded-xl pa-6 h-100 d-flex flex-column" elevation="0">
          <!-- Stars -->
          <div class="d-flex align-center gap-1 mb-4 text-warning">
            <v-icon size="small" v-for="i in 5" :key="i">mdi-star</v-icon>
          </div>
          
          <!-- Quote -->
          <p class="text-body-2 text-medium-emphasis font-italic lh-relaxed flex-grow-1 mb-6">
            "{{ testimonial.quote }}"
          </p>
          
          <!-- Author -->
          <div class="d-flex align-center gap-3 pt-2">
            <v-avatar :color="testimonial.color" variant="tonal" size="36">
              <span class="text-caption font-weight-bold">{{ testimonial.avatarText }}</span>
            </v-avatar>
            <div>
              <div class="text-caption font-weight-bold text-white">{{ testimonial.author }}</div>
              <div class="text-caption text-medium-emphasis text-xs">{{ testimonial.title }}</div>
            </div>
          </div>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<style scoped>
.border-t {
  border-top: 1px solid rgba(255, 255, 255, 0.05) !important;
}
.max-w-7xl {
  max-width: 1280px;
}
.max-w-2xl {
  max-width: 672px;
}
.letter-spacing-wide {
  letter-spacing: 0.1em;
}
.gap-1 { gap: 4px; }
.gap-3 { gap: 12px; }
.lh-relaxed {
  line-height: 1.625;
}
.text-xs {
  font-size: 10px !important;
}

.glass-panel {
  background: rgba(255, 255, 255, 0.03) !important;
  backdrop-filter: blur(12px);
  transition: transform 0.3s ease, border-color 0.3s ease;
}
.glass-panel.border {
  border: 1px solid rgba(255, 255, 255, 0.05) !important;
}
.glass-panel:hover {
  transform: translateY(-4px);
  border-color: rgba(255, 255, 255, 0.1) !important;
}
</style>

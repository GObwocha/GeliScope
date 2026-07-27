<script setup>
const testimonials = [
  {
    name: 'Sarah M.',
    role: 'Parent of Form 2 Student',
    quote: '"I used to look at the term report and wonder how to help. Now, GeliScope tells me exactly which math formulas my daughter struggles with. The weekend improvement steps are a lifesaver."',
    avatar: 'https://api.dicebear.com/7.x/avataaars/svg?seed=Sarah',
    color: '#AB7DE3' // Purple
  },
  {
    name: 'Mr. Omondi',
    role: 'Head of Mathematics',
    quote: '"Marking 150 scripts used to take my entire weekend. Now, I define the rubric once, scan the papers, and the MySQL sync updates the TSC roster instantly. It\'s brilliant."',
    avatar: 'https://api.dicebear.com/7.x/avataaars/svg?seed=Omondi',
    color: '#51FAAA' // Green
  },
  {
    name: 'David K.',
    role: 'Form 4 Candidate',
    quote: '"The AI revision portal is like having a tutor 24/7. When I get a geometry question wrong, it doesn\'t just give me the answer—it shows me the exact step I missed."',
    avatar: 'https://api.dicebear.com/7.x/avataaars/svg?seed=David',
    color: '#FF81FF' // Light Purple
  },
  {
    name: 'Dr. Jane N.',
    role: 'School Principal',
    quote: '"The Bloom\'s taxonomy analytics have completely changed how we do teacher evaluations. We can finally see which cognitive skills our students are missing across the board."',
    avatar: 'https://api.dicebear.com/7.x/avataaars/svg?seed=Jane',
    color: '#51FAAA' // Green
  },
  {
    name: 'Peter W.',
    role: 'Parent of Form 1 Student',
    quote: '"Getting WhatsApp alerts when my son finishes an exam, along with a link to his skill profile, keeps me involved in his education without feeling overwhelmed."',
    avatar: 'https://api.dicebear.com/7.x/avataaars/svg?seed=Peter',
    color: '#AB7DE3' // Purple
  },
  {
    name: 'Grace',
    role: 'Tutor',
    quote: '"I use GeliScope to identify my students\' weak points before our sessions even start. The diagnostic accuracy is incredible."',
    avatar: 'https://api.dicebear.com/7.x/avataaars/svg?seed=Grace',
    color: '#FF81FF' // Light Purple
  }
]
</script>

<template>
  <div class="testimonials-page position-relative w-100 overflow-hidden pb-16">
    <div class="glow-blur-purple epx; opacity: 0.3;"></div>
    <div class="glow-blur-green epx; opacity: 0.3;"></div>

    <v-container class="pt-16 mt-8 position-relative z-10">
      <div class="text-center max-w-3xl mx-auto mb-16">
        <v-chip color="primary" variant="outlined" class="mb-6 font-weight-bold border-opacity-50 e 125, 227, 0.05);">
          Success Stories
        </v-chip>
        <h1 class="text-h2 font-weight-black tracking-tight mb-4 text-white">
          Loved by <span class="text-gradient">Educators & Parents</span>
        </h1>
        <p class="text-h6 text-muted font-weight-light">
          See how GeliScope is transforming education across Kenyan schools.
        </p>
      </div>

      <v-row class="masonry-grid">
        <v-col cols="12" md="6" lg="4" v-for="(testimonial, index) in testimonials" :key="index">
          <div class="bento-card p-8 rounded-xl pa-8 h-100 d-flex flex-column justify-space-between relative group">
            
            <v-icon size="48" :color="testimonial.color" class="position-absolute opacity-10 e0px; right: 20px;">mdi-format-quote-open</v-icon>

            <p class="text-body-1 text-white font-weight-medium leading-relaxed mb-8 position-relative z-10 e.1rem !important;">
              {{ testimonial.quote }}
            </p>

            <div class="d-flex align-center mt-auto position-relative z-10">
              <v-avatar size="50" class="mr-4" :style="`border: 2px solid ${testimonial.color}80;`">
                <v-img :src="testimonial.avatar" alt="Avatar"></v-img>
              </v-avatar>
              <div>
                <h4 class="text-body-1 font-weight-bold text-white mb-0">{{ testimonial.name }}</h4>
                <span class="text-caption" :style="`color: ${testimonial.color};`">{{ testimonial.role }}</span>
              </div>
            </div>

          </div>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<style scoped>
.extracted-style-1 {
  top: 100px; left: -100px; opacity: 0.3;
}

.extracted-style-2 {
  bottom: 100px; right: -100px; opacity: 0.3;
}

.extracted-style-3 {
  border-radius: 100px; background: rgba(171, 125, 227, 0.05);
}

.extracted-style-4 {
  top: 20px; right: 20px;
}

.extracted-style-5 {
  font-size: 1.1rem !important;
}

.max-w-3xl { max-width: 800px; }
.z-10 { z-index: 10; }
.leading-relaxed { line-height: 1.6; }
</style>

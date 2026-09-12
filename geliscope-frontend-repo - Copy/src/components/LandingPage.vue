<script setup>
import LandingHero from './LandingHero.vue'
import LandingFeatures from './LandingFeatures.vue'
import LandingPricing from './LandingPricing.vue'
import LandingTestimonials from './LandingTestimonials.vue'
import LandingFAQ from './LandingFAQ.vue'
import LandingFooter from './LandingFooter.vue'
</script>

<template>
  <div class="bg-brand-dark text-slate-100 antialiased selection:bg-brand-purple selection:text-white">
    <main>
      <LandingHero />
      <LandingFeatures />
      <LandingPricing />
      <LandingTestimonials />
      <LandingFAQ />
      <LandingFooter />
    </main>
  </div>
</template>

<style>
body {
  background-color: #0B1120;
  color: #F8FAFC;
  font-family: 'Spline Sans', sans-serif;
  overflow-x: hidden;
}

h1, h2, h3, h4, .font-heading {
  font-family: 'Outfit', sans-serif;
}

/* Vertical Light Curtain / Aurora Backdrop Effect matching IMAGE_6 & IMAGE_2 */
.aurora-bg {
  background: 
    radial-gradient(ellipse 65% 55% at 50% -10%, rgba(34, 211, 238, 0.22) 0%, rgba(139, 92, 246, 0.28) 45%, rgba(11, 17, 32, 0) 100%),
    repeating-linear-gradient(90deg, rgba(255, 255, 255, 0.015) 0px, rgba(255, 255, 255, 0.015) 2px, transparent 2px, transparent 64px);
}

.aurora-footer {
  background: 
    radial-gradient(circle at 50% 25%, rgba(34, 211, 238, 0.25) 0%, rgba(139, 92, 246, 0.35) 30%, rgba(11, 17, 32, 0.95) 75%);
}

/* Subtle Glassmorphism Card Style */
.glass-panel {
  background: linear-gradient(135deg, rgba(31, 41, 55, 0.6) 0%, rgba(17, 24, 39, 0.8) 100%);
  border: 1px solid rgba(255, 255, 255, 0.08);
  backdrop-filter: blur(16px);
  box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.5);
}

.glass-panel-glow {
  position: relative;
  background: linear-gradient(180deg, rgba(30, 41, 59, 0.8) 0%, rgba(15, 23, 42, 0.95) 100%);
  border: 1px solid rgba(34, 211, 238, 0.35);
  box-shadow: 0 0 35px -5px rgba(34, 211, 238, 0.25);
}

/* Glow Orb Utility */
.glow-orb {
  background: radial-gradient(circle at 35% 35%, #67e8f9 0%, #818cf8 50%, #4338ca 100%);
  box-shadow: 0 0 45px rgba(34, 211, 238, 0.55), inset 0 0 15px rgba(255, 255, 255, 0.6);
}

/* Card Radial Shimmer Effect */
.card-radial-highlight {
  background: radial-gradient(circle at 50% 0%, rgba(34, 211, 238, 0.15) 0%, rgba(17, 24, 39, 0) 70%);
}
</style>

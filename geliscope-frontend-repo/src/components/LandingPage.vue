<script setup>
import LandingHeader from './LandingHeader.vue'
import LandingHero from './LandingHero.vue'
import LandingFeatures from './LandingFeatures.vue'
import LandingTestimonials from './LandingTestimonials.vue'
import LandingPricing from './LandingPricing.vue'
import LandingFAQ from './LandingFAQ.vue'
import LandingFooter from './LandingFooter.vue'
</script>

<template>
  <v-layout class="landing-page position-relative overflow-hidden w-100 bg-background">
    <LandingHeader />
    <v-main class="pt-0">
      <LandingHero />
      <LandingFeatures />
      <LandingTestimonials />
      <LandingPricing />
      <LandingFAQ />
      <LandingFooter />
    </v-main>
  </v-layout>
</template>

<style>
/* Global styles for the landing page */
body {
  background-color: #0B1120;
  color: #F8FAFC;
  font-family: 'Outfit', -apple-system, sans-serif;
}

.font-heading {
  font-family: 'Outfit', sans-serif !important;
}
</style>

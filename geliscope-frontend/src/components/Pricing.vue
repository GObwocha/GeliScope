<script setup>
import { ref } from 'vue'

const isAnnual = ref(true)

const tiers = [
 {
 name: 'Free',
 description: 'Perfect for individual students testing out the AI OCR.',
 monthlyPrice: 0,
 annualPrice: 0,
 color: '#616083',
 popular: false,
 features: [
 'Up to 5 exam scans per month',
 'Basic Bloom\'s taxonomy breakdown',
 'Community forum access',
 'Standard email support'
 ]
 },
 {
 name: 'Starter',
 description: 'For parents wanting deeper insights into their child\'s progress.',
 monthlyPrice: 15,
 annualPrice: 12,
 color: '#51FAAA', // Green
 popular: false,
 features: [
 'Unlimited exam scans',
 'Detailed skill profiles',
 'Actionable weekend improvement steps',
 'Parent portal access',
 'Priority email support'
 ]
 },
 {
 name: 'Pro',
 description: 'For teachers and tutors managing entire class streams.',
 monthlyPrice: 49,
 annualPrice: 39,
 color: '#AB7DE3', // Purple
 popular: true,
 features: [
 'Everything in Starter',
 'Automated teacher marking rubrics',
 'Batch scanning for 50+ students',
 'Class-wide error aggregation',
 'Direct WhatsApp/SMS integration',
 '24/7 Priority support'
 ]
 },
 {
 name: 'Enterprise',
 description: 'For schools and districts requiring hybrid database sync.',
 monthlyPrice: 'Custom',
 annualPrice: 'Custom',
 color: '#FF81FF', // Light Purple
 popular: false,
 features: [
 'Everything in Pro',
 'Relational MySQL sync for TSC rosters',
 'Unlimited teacher accounts',
 'Custom leaderboard and analytics',
 'Dedicated success manager',
 'On-premise deployment options'
 ]
 }
]
</script>

<template>
 <div class="pricing-page position-relative w-100 overflow-hidden pb-16">
 <div class="glow-blur-purple e%); opacity: 0.3;"></div>
 <div class="glow-blur-green e0%; opacity: 0.2;"></div>

 <v-container class="pt-16 mt-8 position-relative z-10">
 <div class="text-center max-w-3xl mx-auto mb-16">
 <v-chip color="secondary" variant="outlined" class="mb-6 font-weight-bold border-opacity-50 e 250, 170, 0.05);">
 Transparent Pricing
 </v-chip>
 <h1 class="text-h2 font-weight-black tracking-tight mb-4 ">
 Invest in <span class="text-gradient">Quality Education</span>
 </h1>
 <p class="text-h6 text-muted font-weight-light mb-8">
 Whether you're a single student, a concerned parent, or an entire school district, we have a plan for you.
 </p>

 <!-- Billing Toggle -->
 <div class="d-flex align-center justify-center gap-3">
 <span :class="!isAnnual ? ' font-weight-bold' : 'text-muted'">Monthly</span>
 <v-switch v-model="isAnnual" color="secondary" hide-details density="compact" inset class="flex-grow-0"></v-switch>
 <span :class="isAnnual ? ' font-weight-bold' : 'text-muted'">Annually <v-chip size="x-small" color="secondary" class="ml-2">Save 20%</v-chip></span>
 </div>
 </div>

 <v-row>
 <v-col v-for="tier in tiers" :key="tier.name" cols="12" sm="6" lg="3">
 <div class="bento-card h-100 rounded-xl pa-8 d-flex flex-column" :style="tier.popular ? `border-color: ${tier.color}; box-shadow: 0 0 20px ${tier.color}33; transform: scale(1.05); z-index: 2;` : ''">
 
 <div v-if="tier.popular" class="position-absolute w-100 text-center e; padding: 4px 0;">
 <span class="text-caption font-weight-bold uppercase tracking-widest">Most Popular</span>
 </div>

 <div :class="tier.popular ? 'mt-4' : ''">
 <h3 class="text-h5 font-weight-bold mb-2" :style="`color: ${tier.color}`">{{ tier.name }}</h3>
 <p class="text-body-2 text-muted mb-6 emin-height: 40px;">{{ tier.description }}</p>
 
 <div class="mb-8">
 <template v-if="typeof tier.monthlyPrice === 'number'">
 <span class="text-h3 font-weight-bold ">${{ isAnnual ? tier.annualPrice : tier.monthlyPrice }}</span>
 <span class="text-muted">/mo</span>
 </template>
 <template v-else>
 <span class="text-h3 font-weight-bold ">{{ tier.monthlyPrice }}</span>
 </template>
 </div>

 <v-btn block :color="tier.popular ? 'primary' : 'surface'" :variant="tier.popular ? 'flat' : 'outlined'" class="pill-btn mb-8 border-opacity-50" size="large">
 {{ tier.name === 'Enterprise' ? 'Contact Sales' : 'Get Started' }}
 </v-btn>
 </div>

 <div class="flex-grow-1">
 <div class="text-caption font-weight-bold mb-4 uppercase">What's included:</div>
 <v-list class="bg-transparent pa-0">
 <v-list-item v-for="(feature, idx) in tier.features" :key="idx" class="pa-0 mb-3 min-h-0">
 <template v-slot:prepend>
 <v-icon :color="tier.color" size="small" class="mr-3">mdi-check-circle</v-icon>
 </template>
 <v-list-item-title class="text-body-2 text-muted ete-space: normal;">{{ feature }}</v-list-item-title>
 </v-list-item>
 </v-list>
 </div>

 </div>
 </v-col>
 </v-row>
 </v-container>
 </div>
</template>

<style scoped>
.extracted-style-1 {
 top: 0px; left: 20%; transform: translateX(-50%); opacity: 0.3;
}

.extracted-style-2 {
 bottom: 100px; right: 10%; opacity: 0.2;
}

.extracted-style-3 {
 border-radius: 100px; background: rgba(81, 250, 170, 0.05);
}

.extracted-style-4 {
 top: 0; left: 0; background: #AB7DE3; padding: 4px 0;
}

.extracted-style-5 {
 min-height: 40px;
}

.extracted-style-6 {
 white-space: normal;
}

.max-w-3xl { max-width: 800px; }
.z-10 { z-index: 10; }
.uppercase { text-transform: uppercase; }
.tracking-widest { letter-spacing: 0.1em; }
</style>

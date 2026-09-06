<script setup>
import { ref } from 'vue'

const plans = ref([
  {
    name: "Student",
    description: "The essential companion for individual scholars.",
    price: "Free",
    period: "forever",
    features: [
      "1 Student Account",
      "Access to exam archives & revision materials",
      "Track progress on class leaderboards",
      "Standard email support"
    ],
    buttonText: "Sign Up Free",
    buttonVariant: "outlined",
    featured: false
  },
  {
    name: "Family",
    badge: "Most Popular",
    description: "The optimal plan for keeping parents seamlessly informed.",
    price: "Ksh 500",
    period: "/ per month",
    features: [
      "Everything in the Student plan",
      "Up to 3 Student Accounts + 2 Parent Accounts",
      "Real-time Parent Radar & Performance digests",
      "Instantly see where your child is struggling",
      "Automated SMS & Email notifications on exam scores"
    ],
    buttonText: "Start Free Trial",
    buttonVariant: "elevated",
    featured: true
  },
  {
    name: "Educators & Schools",
    description: "Full-scale intelligence for teachers and schools.",
    price: "Custom",
    period: "/ billed yearly",
    features: [
      "Unlimited Student, Parent & Teacher Seats",
      "Autonomous grading via the Upload Centre",
      "Actionable curriculum insights & class analytics",
      "Automate report cards and save hours of marking",
      "Dedicated Success Manager & School Integration"
    ],
    buttonText: "Talk to Sales",
    buttonVariant: "outlined",
    featured: false
  }
])
</script>

<template>
  <v-container class="py-16 max-w-7xl mx-auto" id="pricing">
    <div class="text-center max-w-2xl mx-auto mb-16">
      <h2 class="text-h3 font-weight-black text-white font-heading">
        Straightforward, transparent pricing
      </h2>
    </div>
    
    <v-row class="align-stretch">
      <v-col 
        cols="12" 
        md="4" 
        v-for="(plan, index) in plans" 
        :key="index"
        :class="plan.featured ? 'mt-md-n4' : ''"
      >
        <v-card 
          class="h-100 d-flex flex-column rounded-xl pa-8 transition-all"
          :class="plan.featured ? 'glass-panel-glow border-primary' : 'glass-panel border'"
          elevation="0"
        >
          <!-- Aurora background for featured -->
          <div v-if="plan.featured" class="featured-bg"></div>

          <div class="position-relative z-10 flex-grow-1">
            <div class="d-flex justify-space-between align-center mb-1">
              <span class="text-caption font-weight-bold text-uppercase tracking-wider" :class="plan.featured ? 'text-secondary' : 'text-medium-emphasis'">
                {{ plan.name }}
              </span>
              <v-chip 
                v-if="plan.badge" 
                color="secondary" 
                variant="outlined" 
                size="x-small" 
                class="font-weight-bold text-uppercase"
              >
                {{ plan.badge }}
              </v-chip>
            </div>
            
            <p class="text-caption text-medium-emphasis mb-6 lh-relaxed">
              {{ plan.description }}
            </p>
            
            <div class="d-flex align-baseline gap-1 mb-8">
              <span class="text-h3 font-weight-black text-white font-heading">{{ plan.price }}</span>
              <span class="text-caption text-medium-emphasis">{{ plan.period }}</span>
            </div>
            
            <v-btn 
            v-if="plan.name == 'Student' || plan.name == 'Family'"
              block 
              :color="plan.featured ? 'secondary' : 'white'" 
              :variant="plan.buttonVariant"
              class="rounded-lg text-uppercase font-weight-bold tracking-wider mb-8"
              :class="plan.featured ? 'featured-btn' : 'opacity-80'"
              to="/register"
            >
              {{ plan.buttonText }}
            </v-btn>

            <v-btn 
            v-else
              block 
              color="white" 
              :variant="plan.buttonVariant"
              class="rounded-lg text-uppercase font-weight-bold tracking-wider mb-8 opacity-80"
              href="#demo"
            >
              {{ plan.buttonText }}
            </v-btn>
            
            <div class="border-t pt-6">
              <ul class="list-unstyled space-y-4 text-caption text-medium-emphasis">
                <li class="d-flex align-start gap-3" v-for="(feat, fIndex) in plan.features" :key="fIndex">
                  <v-icon color="secondary" size="small" class="mt-1">mdi-check</v-icon>
                  <span>{{ feat }}</span>
                </li>
              </ul>
            </div>
          </div>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<style scoped>
.border-t { border-top: 1px solid rgba(255, 255, 255, 0.05) !important; }
.border { border: 1px solid rgba(255, 255, 255, 0.05) !important; }
.border-primary { border: 1px solid rgba(34, 211, 238, 0.35) !important; }
.max-w-7xl { max-width: 1280px; }
.max-w-2xl { max-width: 672px; }
.lh-relaxed { line-height: 1.5; }
.tracking-wider { letter-spacing: 0.05em; }
.gap-1 { gap: 4px; }
.gap-3 { gap: 12px; }
.space-y-4 > li { margin-bottom: 16px; }
.list-unstyled { list-style-type: none; padding-left: 0; }
.opacity-80 { opacity: 0.8; }
.z-10 { z-index: 10; }

.glass-panel {
  background: linear-gradient(135deg, rgba(31, 41, 55, 0.6) 0%, rgba(17, 24, 39, 0.8) 100%) !important;
  backdrop-filter: blur(16px);
}
.glass-panel:hover {
  border-color: rgba(255, 255, 255, 0.2) !important;
}

.glass-panel-glow {
  background: linear-gradient(180deg, rgba(30, 41, 59, 0.8) 0%, rgba(15, 23, 42, 0.95) 100%) !important;
  box-shadow: 0 0 35px -5px rgba(34, 211, 238, 0.25);
}

.featured-bg {
  position: absolute;
  top: 0; left: 0; right: 0; bottom: 0;
  background: linear-gradient(to bottom, rgba(34, 211, 238, 0.15), rgba(139, 92, 246, 0.1), transparent);
  border-radius: 24px;
  pointer-events: none;
}

.featured-btn {
  background: linear-gradient(to right, #2563EB, #22D3EE) !important;
  color: white !important;
  border: none !important;
  transition: all 0.3s ease;
}
.featured-btn:hover {
  box-shadow: 0 10px 15px -3px rgba(34, 211, 238, 0.3);
  opacity: 0.9;
}
</style>

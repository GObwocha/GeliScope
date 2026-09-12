<script setup>
import { ref } from 'vue'

const faqs = ref([
  {
    question: "What is an AI Educational Support System?",
    answer: "GeliScope is a specialized pedagogic AI platform designed to provide round-the-clock individual tutoring for students, proactive learning summaries for parents, and automated rubric grading for educators."
  },
  {
    question: "Do educators need coding skills or technical expertise?",
    answer: "No technical expertise is required. Teachers and parents can customize rubrics, generate interactive practice sets, and review student progress in an intuitive, zero-code visual interface."
  },
  {
    question: "How does autonomous grading work for teachers?",
    answer: "Teachers simply upload assignments or exams to our dedicated Upload Centre. The system automatically grades the submissions, securely logs the results in the Exam Archives, and instantly gives you detailed class performance analytics without hours of manual marking."
  },
  {
    question: "How do parents stay informed about their child's progress?",
    answer: "We ensure parents are always in the loop without having to ask. GeliScope provides a real-time Parent Dashboard and can securely send SMS and Email notifications so you instantly know when your child takes an exam, struggles, or excels."
  },
  {
    question: "How are student privacy and data protected?",
    answer: "All student data is strictly encrypted end-to-end and is 100% compliant with FERPA, COPPA, and GDPR-K regulations. We never sell student data or use personal submissions to train public models."
  }
])
</script>

<template>
  <v-container class="py-16 max-w-7xl mx-auto border-t" id="faq">
    <v-row class="mt-8">
      <!-- Left Column: Title and Contact CTA -->
      <v-col cols="12" lg="4" class="mb-8 lg:mb-0 pr-lg-8">
        <h2 class="text-h3 font-weight-black text-white mb-6">
          Frequently asked questions
        </h2>
        <p class="text-body-1 text-medium-emphasis mb-8">
          Got questions? Look through our answers or reach out to our support team directly.
        </p>
        <v-btn 
          color="white" 
          variant="flat" 
          class="rounded-pill text-uppercase font-weight-bold contact-btn" 
          size="large"
          href="#demo"
        >
          Contact Us
        </v-btn>
      </v-col>

      <!-- Right Column: Accordion List -->
      <v-col cols="12" lg="8">
        <v-expansion-panels variant="accordion" bg-color="transparent" class="faq-panels">
          <v-expansion-panel 
            v-for="(faq, index) in faqs" 
            :key="index"
            class="mb-4 glass-panel border rounded-xl overflow-hidden"
            elevation="0"
          >
            <v-expansion-panel-title class="font-weight-bold text-subtitle-1 text-white py-6">
              {{ faq.question }}
              <template v-slot:actions="{ expanded }">
                <v-icon :color="expanded ? 'secondary' : 'grey'" class="ml-4 shrink-0 font-mono text-h6">
                  {{ expanded ? 'mdi-minus' : 'mdi-plus' }}
                </v-icon>
              </template>
            </v-expansion-panel-title>
            <v-expansion-panel-text class="text-medium-emphasis text-body-2 pb-6 pt-0">
              {{ faq.answer }}
            </v-expansion-panel-text>
          </v-expansion-panel>
        </v-expansion-panels>
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
.glass-panel {
  background: rgba(255, 255, 255, 0.03) !important;
  backdrop-filter: blur(12px);
}
.glass-panel.border {
  border: 1px solid rgba(255, 255, 255, 0.05) !important;
}

.faq-panels .v-expansion-panel {
  background-color: transparent !important;
}
.faq-panels .v-expansion-panel::before {
  box-shadow: none !important;
}

.contact-btn {
  letter-spacing: 1px;
  color: #0B1120 !important;
  transition: all 0.3s ease;
}
.contact-btn:hover {
  background-color: #f1f5f9 !important;
  box-shadow: 0 10px 15px -3px rgba(34, 211, 238, 0.2);
  transform: scale(1.05);
}
</style>

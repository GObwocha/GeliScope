<script setup>
import { ref } from 'vue'

const faqs = ref([
  {
    question: "What is an AI Educational Support System?",
    answer: "GeliScope is a specialized pedagogic AI platform designed to provide round-the-clock individual tutoring for students, proactive learning summaries for parents, and automated rubric grading for educators.",
    open: true
  },
  {
    question: "Do educators need coding skills or technical expertise?",
    answer: "No technical expertise is required. Teachers and parents can customize rubrics, generate interactive practice sets, and review student progress in an intuitive, zero-code visual interface.",
    open: false
  },
  {
    question: "How does the AI adapt to specific curriculum standards?",
    answer: "GeliScope supports Common Core, International Baccalaureate (IB), AP, Cambridge, and custom school district syllabi. Teachers simply upload curriculum documents to align answers automatically.",
    open: false
  },
  {
    question: "Does it support multiple spoken languages?",
    answer: "Yes, GeliScope natively supports over 95 languages for both text interaction and spoken voice conversations, making it ideal for bilingual households and international schools.",
    open: false
  },
  {
    question: "How are student privacy and data protected?",
    answer: "All student data is strictly encrypted end-to-end and is 100% compliant with FERPA, COPPA, and GDPR-K regulations. We never sell student data or use personal submissions to train public models.",
    open: false
  }
])

const toggleFaq = (index) => {
  faqs.value[index].open = !faqs.value[index].open;
}
</script>

<template>
<section class="py-24 px-4 sm:px-6 max-w-7xl mx-auto border-t border-white/5" id="faq">
<div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
<!-- Left Column -->
<div class="lg:col-span-4 space-y-6">
<h2 class="text-3xl sm:text-4xl font-extrabold text-white leading-tight font-heading">
            Frequently asked questions
          </h2>
<p class="text-sm text-brand-muted leading-relaxed">
            Got questions? Look through our answers or reach out to our support team directly.
          </p>
<div class="pt-2">
<a class="inline-block px-7 py-3 rounded-full bg-white text-brand-dark font-semibold text-xs uppercase tracking-wider shadow-lg shadow-white/10 hover:shadow-cyan-400/20 hover:scale-105 transition-all" href="#demo">
              Contact Us
            </a>
</div>
</div>
<!-- Right Column -->
<div class="lg:col-span-8 space-y-3.5" data-purpose="faq-accordion-container">
  
  <div v-for="(faq, index) in faqs" :key="index" class="glass-panel rounded-2xl border border-white/5 p-5 transition-all" :class="{ 'border-white/10': faq.open }">
    <button class="w-full flex justify-between items-center text-left text-sm font-semibold text-white" @click="toggleFaq(index)" type="button">
      <span>{{ faq.question }}</span>
      <span class="text-lg font-mono ml-4 shrink-0" :class="faq.open ? 'text-brand-cyan' : 'text-brand-muted'">
        {{ faq.open ? '−' : '+' }}
      </span>
    </button>
    <div class="mt-3 text-xs sm:text-sm text-brand-muted leading-relaxed" v-show="faq.open">
      {{ faq.answer }}
    </div>
  </div>

</div>
</div>
</section>
</template>

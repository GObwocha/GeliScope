<template>
<!-- BEGIN: FAQSection -->
<!-- Faithful implementation of IMAGE_3 split layout -->
<section class="py-24 px-4 sm:px-6 max-w-7xl mx-auto border-t border-white/5" id="faq">
<div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
<!-- Left Column: Title and Contact CTA (IMAGE_3 Style) -->
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
<!-- Right Column: Accordion List (IMAGE_3 Style) -->
<div class="lg:col-span-8 space-y-3.5" data-purpose="faq-accordion-container">
<!-- Accordion Item 1 (Default Open) -->
<div class="glass-panel rounded-2xl border border-white/10 p-5 transition-all">
<button class="w-full flex justify-between items-center text-left text-sm font-semibold text-white" onclick="toggleFaq(this)" type="button">
<span>What is an AI Educational Support System?</span>
<span class="text-brand-cyan text-lg font-mono ml-4 shrink-0">−</span>
</button>
<div class="mt-3 text-xs sm:text-sm text-brand-muted leading-relaxed">
              GeliScope is a specialized pedagogic AI platform designed to provide round-the-clock individual tutoring for students, proactive learning summaries for parents, and automated rubric grading for educators.
            </div>
</div>
<!-- Accordion Item 2 -->
<div class="glass-panel rounded-2xl border border-white/5 p-5 transition-all">
<button class="w-full flex justify-between items-center text-left text-sm font-semibold text-white" onclick="toggleFaq(this)" type="button">
<span>Do educators need coding skills or technical expertise?</span>
<span class="text-brand-muted text-lg font-mono ml-4 shrink-0">+</span>
</button>
<div class="mt-3 text-xs sm:text-sm text-brand-muted leading-relaxed hidden">
              No technical expertise is required. Teachers and parents can customize rubrics, generate interactive practice sets, and review student progress in an intuitive, zero-code visual interface.
            </div>
</div>
<!-- Accordion Item 3 -->
<div class="glass-panel rounded-2xl border border-white/5 p-5 transition-all">
<button class="w-full flex justify-between items-center text-left text-sm font-semibold text-white" onclick="toggleFaq(this)" type="button">
<span>How does the AI adapt to specific curriculum standards?</span>
<span class="text-brand-muted text-lg font-mono ml-4 shrink-0">+</span>
</button>
<div class="mt-3 text-xs sm:text-sm text-brand-muted leading-relaxed hidden">
              GeliScope supports Common Core, International Baccalaureate (IB), AP, Cambridge, and custom school district syllabi. Teachers simply upload curriculum documents to align answers automatically.
            </div>
</div>
<!-- Accordion Item 4 -->
<div class="glass-panel rounded-2xl border border-white/5 p-5 transition-all">
<button class="w-full flex justify-between items-center text-left text-sm font-semibold text-white" onclick="toggleFaq(this)" type="button">
<span>Does it support multiple spoken languages?</span>
<span class="text-brand-muted text-lg font-mono ml-4 shrink-0">+</span>
</button>
<div class="mt-3 text-xs sm:text-sm text-brand-muted leading-relaxed hidden">
              Yes, GeliScope natively supports over 95 languages for both text interaction and spoken voice conversations, making it ideal for bilingual households and international schools.
            </div>
</div>
<!-- Accordion Item 5 -->
<div class="glass-panel rounded-2xl border border-white/5 p-5 transition-all">
<button class="w-full flex justify-between items-center text-left text-sm font-semibold text-white" onclick="toggleFaq(this)" type="button">
<span>How are student privacy and data protected?</span>
<span class="text-brand-muted text-lg font-mono ml-4 shrink-0">+</span>
</button>
<div class="mt-3 text-xs sm:text-sm text-brand-muted leading-relaxed hidden">
              All student data is strictly encrypted end-to-end and is 100% compliant with FERPA, COPPA, and GDPR-K regulations. We never sell student data or use personal submissions to train public models.
            </div>
</div>
</div>
</div>
</section>
<!-- END: FAQSection -->
</template>

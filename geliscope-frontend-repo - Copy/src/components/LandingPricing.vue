<template>
<!-- BEGIN: PricingSection -->
<section class="py-24 px-4 sm:px-6 max-w-7xl mx-auto relative" id="pricing">
<!-- Pricing Header mirroring IMAGE_4 -->
<div class="flex flex-col md:flex-row md:items-end justify-between mb-16 gap-6">
<div>
<span class="text-xs uppercase tracking-widest text-brand-cyan font-semibold block mb-2">Transparent Plans</span>
<h2 class="text-3xl sm:text-5xl font-extrabold text-white tracking-tight">
            Select a plan that suits <br class="hidden sm:inline"/> your learning journey.
          </h2>
</div>
<p class="text-brand-muted text-sm sm:text-base max-w-md">
          Simple, transparent pricing built for individual students, families, or full educational districts.
        </p>
</div>
<!-- 3 Tier Cards matching IMAGE_4 -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-stretch">
<!-- Card 1: Starter -->
<div class="glass-panel rounded-3xl p-8 flex flex-col justify-between hover:border-white/20 transition-all duration-300">
<div>
<span class="text-xs font-semibold uppercase tracking-wider text-brand-muted block">Starter</span>
<p class="text-xs text-brand-muted mt-1">The essential companion for individual scholars.</p>
<div class="mt-6 mb-6 flex items-baseline gap-1">
<span class="text-4xl font-extrabold text-white font-heading">$9</span>
<span class="text-xs text-brand-muted">/ per month</span>
</div>
<a class="w-full block text-center py-2.5 px-4 rounded-xl bg-white/10 hover:bg-white/20 border border-white/15 text-xs font-semibold tracking-wider uppercase text-white transition-colors" href="#demo">
              Get Started
            </a>
<!-- Checklist -->
<ul class="mt-8 space-y-3.5 text-xs text-brand-muted border-t border-white/5 pt-6">
<li class="flex items-center gap-2.5">
<svg class="w-4 h-4 text-brand-cyan shrink-0" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
<span>1 Student Account</span>
</li>
<li class="flex items-center gap-2.5">
<svg class="w-4 h-4 text-brand-cyan shrink-0" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
<span>Up to 1,000 AI tutoring prompts / mo</span>
</li>
<li class="flex items-center gap-2.5">
<svg class="w-4 h-4 text-brand-cyan shrink-0" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
<span>Text-only knowledge base exploration</span>
</li>
<li class="flex items-center gap-2.5">
<svg class="w-4 h-4 text-brand-cyan shrink-0" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
<span>Basic weekly parent digest</span>
</li>
<li class="flex items-center gap-2.5">
<svg class="w-4 h-4 text-brand-cyan shrink-0" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
<span>Standard email support</span>
</li>
</ul>
</div>
</div>
<!-- Card 2: Most Popular (Featured with glowing background matching IMAGE_4) -->
<div class="glass-panel-glow rounded-3xl p-8 flex flex-col justify-between relative transform lg:-translate-y-2">
<!-- Aurora vertical beam pattern inside the featured card -->
<div class="absolute inset-0 bg-gradient-to-b from-brand-cyan/15 via-brand-purple/10 to-transparent rounded-3xl pointer-events-none"></div>
<div class="relative z-10">
<div class="flex justify-between items-center">
<span class="text-xs font-semibold uppercase tracking-wider text-brand-cyan">Most Popular</span>
<span class="px-2.5 py-0.5 rounded-full bg-brand-cyan/20 border border-brand-cyan/40 text-[10px] uppercase font-bold text-brand-cyan tracking-wider">Recommended</span>
</div>
<p class="text-xs text-brand-muted mt-1">The optimal plan for accelerated student excellence &amp; parents.</p>
<div class="mt-6 mb-6 flex items-baseline gap-1">
<span class="text-4xl font-extrabold text-white font-heading">$29</span>
<span class="text-xs text-brand-muted">/ per month</span>
</div>
<a class="w-full block text-center py-2.5 px-4 rounded-xl bg-gradient-to-r from-blue-600 to-brand-cyan hover:from-blue-500 hover:to-cyan-300 text-xs font-semibold tracking-wider uppercase text-white shadow-lg shadow-cyan-500/25 transition-all" href="#demo">
              Try Most Popular
            </a>
<!-- Checklist -->
<ul class="mt-8 space-y-3.5 text-xs text-slate-300 border-t border-white/10 pt-6">
<li class="flex items-center gap-2.5">
<svg class="w-4 h-4 text-brand-cyan shrink-0" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
<span>Up to 3 Student Accounts + 2 Parent Seats</span>
</li>
<li class="flex items-center gap-2.5">
<svg class="w-4 h-4 text-brand-cyan shrink-0" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
<span>Unlimited conversational tutoring</span>
</li>
<li class="flex items-center gap-2.5">
<svg class="w-4 h-4 text-brand-cyan shrink-0" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
<span>Voice input &amp; spoken feedback</span>
</li>
<li class="flex items-center gap-2.5">
<svg class="w-4 h-4 text-brand-cyan shrink-0" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
<span>Real-Time Emotional &amp; Mastery Radar</span>
</li>
<li class="flex items-center gap-2.5">
<svg class="w-4 h-4 text-brand-cyan shrink-0" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
<span>Multi-language instant translation</span>
</li>
<li class="flex items-center gap-2.5">
<svg class="w-4 h-4 text-brand-cyan shrink-0" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
<span>24/7 Priority tutoring engine access</span>
</li>
</ul>
</div>
</div>
<!-- Card 3: Enterprise / District -->
<div class="glass-panel rounded-3xl p-8 flex flex-col justify-between hover:border-white/20 transition-all duration-300">
<div>
<span class="text-xs font-semibold uppercase tracking-wider text-brand-muted block">Enterprise / School</span>
<p class="text-xs text-brand-muted mt-1">Full-scale intelligence for schools and districts.</p>
<div class="mt-6 mb-6 flex items-baseline gap-1">
<span class="text-4xl font-extrabold text-white font-heading">Custom</span>
<span class="text-xs text-brand-muted">/ billed yearly</span>
</div>
<a class="w-full block text-center py-2.5 px-4 rounded-xl bg-white/10 hover:bg-white/20 border border-white/15 text-xs font-semibold tracking-wider uppercase text-white transition-colors" href="#demo">
              Talk to Sales
            </a>
<!-- Checklist -->
<ul class="mt-8 space-y-3.5 text-xs text-brand-muted border-t border-white/5 pt-6">
<li class="flex items-center gap-2.5">
<svg class="w-4 h-4 text-brand-cyan shrink-0" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
<span>Unlimited Student, Parent &amp; Teacher Seats</span>
</li>
<li class="flex items-center gap-2.5">
<svg class="w-4 h-4 text-brand-cyan shrink-0" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
<span>Autonomous grading &amp; curriculum alignment</span>
</li>
<li class="flex items-center gap-2.5">
<svg class="w-4 h-4 text-brand-cyan shrink-0" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
<span>District-wide security compliance (FERPA &amp; GDPR)</span>
</li>
<li class="flex items-center gap-2.5">
<svg class="w-4 h-4 text-brand-cyan shrink-0" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
<span>Dedicated Success Manager &amp; API Integration</span>
</li>
<li class="flex items-center gap-2.5">
<svg class="w-4 h-4 text-brand-cyan shrink-0" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
<span>Custom fine-tuned pedagogical models</span>
</li>
</ul>
</div>
</div>
</div>
</section>
<!-- END: PricingSection -->
</template>

<template>
<!-- BEGIN: HeroSection -->
<section class="relative min-h-screen pt-36 pb-24 aurora-bg flex flex-col justify-center items-center px-4 sm:px-6 overflow-hidden" id="hero">
<!-- Glow ambient light bar atop the section -->
<div class="absolute top-20 left-1/2 -translate-x-1/2 w-3/4 max-w-4xl h-72 bg-gradient-to-b from-brand-cyan/25 via-brand-purple/20 to-transparent blur-3xl pointer-events-none -z-10"></div>
<!-- Hero Header & Tag -->
<div class="max-w-4xl mx-auto text-center space-y-6">
<!-- Floating Pill Badge -->
<div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-white/10 bg-white/5 backdrop-blur-md shadow-inner text-xs uppercase tracking-widest text-brand-lavender font-medium animate-pulse">
<span class="w-2 h-2 rounded-full bg-brand-cyan animate-ping"></span>
          Next-Gen Educational AI
        </div>
<!-- Main Headline -->
<h1 class="text-4xl sm:text-6xl md:text-7xl font-extrabold tracking-tight text-white leading-[1.12]">
          AI Educational Intelligence <br class="hidden sm:inline"/>
<span class="bg-clip-text text-transparent bg-gradient-to-r from-white via-slate-100 to-brand-cyan">That Never Sleeps</span>
</h1>
<!-- Subheadline text -->
<p class="text-brand-muted text-base sm:text-lg max-w-2xl mx-auto leading-relaxed font-normal">
          Empowering students with 24/7 personalized tutoring, keeping parents seamlessly informed, and liberating teachers with autonomous grading &amp; curriculum insights.
        </p>
<!-- CTA Trigger Button (IMAGE_6 Style) -->
<div class="pt-2 flex justify-center">
<a class="px-8 py-3.5 bg-white text-brand-dark rounded-full font-semibold text-sm tracking-wider uppercase shadow-[0_0_30px_rgba(255,255,255,0.35)] hover:shadow-[0_0_40px_rgba(34,211,238,0.5)] transition-all duration-300 hover:scale-105 active:scale-95" href="#demo">
            Book a Demo
          </a>
</div>
</div>
<!-- Interactive AI Chat Interface Preview Window (Faithful to IMAGE_6) -->
<div class="w-full max-w-3xl mt-14 px-2">
<div class="relative rounded-2xl p-[1.5px] bg-gradient-to-r from-brand-cyan/70 via-brand-purple/50 to-brand-cyan/60 shadow-[0_10px_60px_rgba(34,211,238,0.18)]">
<div class="bg-[#0e1628] rounded-[15px] p-5 sm:p-7 space-y-5">
<!-- Window Title bar / Status -->
<div class="flex items-center justify-between border-b border-white/5 pb-3">
<div class="flex items-center space-x-2">
<span class="h-2.5 w-2.5 rounded-full bg-brand-cyan animate-pulse"></span>
<span class="text-xs font-mono tracking-wider text-brand-muted uppercase">AI Learning Session • Live 24/7</span>
</div>
<div class="flex space-x-1.5">
<span class="w-2.5 h-2.5 rounded-full bg-white/10"></span>
<span class="w-2.5 h-2.5 rounded-full bg-white/10"></span>
<span class="w-2.5 h-2.5 rounded-full bg-white/10"></span>
</div>
</div>
<!-- Greeting Prompt Message Display -->
<div class="min-h-[110px] flex items-start space-x-3 text-left">
<div class="flex-shrink-0 w-8 h-8 rounded-full bg-gradient-to-tr from-brand-purple to-brand-cyan flex items-center justify-center text-xs font-bold text-white shadow-md shadow-brand-purple/30">
                G
              </div>
<div class="bg-white/5 border border-white/10 rounded-2xl rounded-tl-sm px-4 py-3 text-sm sm:text-base text-slate-100 max-w-xl shadow-sm">
<p class="font-medium">
                  Hi 👋 How can <span class="text-brand-cyan font-semibold">GeliScope</span> assist your learning today?
                </p>
<p class="text-xs text-brand-muted mt-1.5">
                  Suggested: Explain Quantum Physics basics, review my calculus proof, or summarize AP History Chapter 4.
                </p>
</div>
</div>
<!-- Chat Mock Console Input Field -->
<div class="relative flex items-center rounded-xl bg-slate-900/90 border border-white/10 px-4 py-3 text-brand-muted focus-within:border-brand-cyan/60 transition-colors">
<button aria-label="Add attachment" class="text-brand-muted hover:text-white transition-colors pr-3" type="button">
<svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M12 4v16m8-8H4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
</svg>
</button>
<input class="w-full bg-transparent border-0 p-0 text-sm text-white placeholder:text-slate-500 focus:ring-0 focus:outline-none" placeholder="Ask any question, paste homework problems, or request a syllabus rubric..." type="text"/>
<!-- Input Action icons: Microphone and Send spark -->
<div class="flex items-center space-x-3 pl-3 border-l border-white/10 text-brand-muted">
<button aria-label="Voice input" class="hover:text-brand-cyan transition-colors" type="button">
<svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
</svg>
</button>
<button aria-label="Submit prompt" class="text-brand-cyan hover:scale-110 transition-transform" type="button">
<svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
</svg>
</button>
</div>
</div>
</div>
</div>
</div>
<!-- Partner Logo Grid (From IMAGE_6) -->
<div class="w-full max-w-5xl mt-20 pt-8 border-t border-white/5 flex flex-wrap items-center justify-around gap-6 text-slate-500 opacity-70">
<div class="flex items-center space-x-2 font-mono text-xs tracking-wider">
<span class="w-2.5 h-2.5 rounded-full bg-purple-500 inline-block"></span>
<span>OXFORD STEM</span>
</div>
<div class="flex items-center space-x-2 font-mono text-xs tracking-wider">
<span class="w-2.5 h-2.5 rounded-full bg-red-500 inline-block"></span>
<span>CAMBRIDGE AI</span>
</div>
<div class="flex items-center space-x-2 font-mono text-xs tracking-wider">
<span class="w-2.5 h-2.5 rounded-full bg-cyan-400 inline-block"></span>
<span>HARVARD EDTECH</span>
</div>
<div class="flex items-center space-x-2 font-mono text-xs tracking-wider">
<span class="w-2.5 h-2.5 rounded-full bg-amber-500 inline-block"></span>
<span>STANFORD LABS</span>
</div>
<div class="flex items-center space-x-2 font-mono text-xs tracking-wider">
<span class="w-2.5 h-2.5 rounded-full bg-blue-500 inline-block"></span>
<span>MIT HORIZON</span>
</div>
</div>
</section>
<!-- END: HeroSection -->
</template>

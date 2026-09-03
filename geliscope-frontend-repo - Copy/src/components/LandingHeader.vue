<template>
<!-- BEGIN: MainHeader -->
<header class="fixed top-0 left-0 right-0 z-50 backdrop-blur-xl bg-brand-dark/70 border-b border-white/5 transition-all duration-300">
<nav aria-label="Main Navigation" class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
<!-- Logo Branding -->
<a class="flex items-center gap-2.5 group" href="#">
<div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-brand-purple to-brand-cyan flex items-center justify-center p-0.5 shadow-lg shadow-brand-cyan/20 group-hover:scale-105 transition-transform duration-300">
<div class="w-full h-full bg-brand-dark rounded-[10px] flex items-center justify-center">
<svg class="w-5 h-5 text-brand-cyan" fill="none" stroke="currentColor" viewbox="0 0 24 24">
<path d="M13 10V3L4 14h7v7l9-11h-7z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
</svg>
</div>
</div>
<span class="font-heading font-extrabold text-xl tracking-wide text-white">Geli<span class="text-brand-cyan">Scope</span></span>
</a>
<!-- Desktop Navigation Menu -->
<div class="hidden md:flex items-center space-x-9 text-sm font-medium text-brand-muted">
<a class="hover:text-white transition-colors duration-200" href="#hero">Home</a>
<a class="hover:text-white transition-colors duration-200" href="#features">Features</a>
<a class="hover:text-white transition-colors duration-200" href="#pricing">Pricing</a>
<a class="hover:text-white transition-colors duration-200" href="#testimonials">Testimonials</a>
<a class="hover:text-white transition-colors duration-200" href="#faq">FAQ</a>
</div>
<!-- Action Buttons -->
<div class="flex items-center gap-4">
<a class="text-sm font-medium text-brand-muted hover:text-white transition-colors px-3 py-2" href="#login">Sign In</a>
<a class="relative inline-flex items-center justify-center px-5 py-2.5 text-xs font-semibold tracking-wider uppercase text-brand-dark bg-white rounded-full hover:bg-slate-100 hover:shadow-lg hover:shadow-white/20 transition-all duration-300 hover:scale-[1.02] active:scale-[0.98]" href="#demo">
          Book a Demo
        </a>
</div>
</nav>
</header>
<!-- END: MainHeader -->
</template>

<template>
<!-- BEGIN: FooterCallToAction -->
<!-- Faithfully reproduces IMAGE_2: Floating sphere, vertical light curtain, clean typography, large watermark -->
<section class="relative pt-24 pb-16 aurora-footer border-t border-white/10 overflow-hidden" id="demo">
<!-- Center Glowing Sphere (IMAGE_2 Style) -->
<div class="flex flex-col items-center justify-center text-center px-4 max-w-3xl mx-auto relative z-10">
<div class="w-14 h-14 rounded-full glow-orb mb-8 animate-bounce transition-transform duration-700"></div>
<h2 class="text-4xl sm:text-6xl font-extrabold text-white tracking-tight font-heading mb-4">
          Let's talk
        </h2>
<p class="text-brand-muted text-sm sm:text-base max-w-md mx-auto mb-8 font-normal leading-relaxed">
          Launch your AI educational intelligence assistant and elevate the modern classroom today.
        </p>
<!-- CTA Pill Button -->
<a class="px-8 py-3 bg-white text-brand-dark rounded-full font-semibold text-xs uppercase tracking-wider shadow-[0_0_35px_rgba(255,255,255,0.4)] hover:shadow-[0_0_50px_rgba(34,211,238,0.6)] hover:scale-105 transition-all" href="#demo">
          Book a Demo
        </a>
</div>
<!-- Links Grid mirroring IMAGE_2 layout -->
<div class="max-w-7xl mx-auto px-6 mt-24 pt-12 border-t border-white/10 grid grid-cols-1 md:grid-cols-12 gap-8 text-xs relative z-10">
<!-- Left Brand Description -->
<div class="md:col-span-6 space-y-4">
<div class="flex items-center gap-2">
<span class="font-heading font-extrabold text-lg text-white">Geli<span class="text-brand-cyan">Scope</span></span>
</div>
<p class="text-brand-muted max-w-sm leading-relaxed">
            Your 24/7 educational intelligence platform — ready to empower students, inform parents, and elevate teachers.
          </p>
</div>
<!-- Links Column 1 -->
<div class="md:col-span-3 space-y-2.5">
<h4 class="font-semibold text-white uppercase tracking-wider text-[11px]">Company</h4>
<ul class="space-y-2 text-brand-muted">
<li><a class="hover:text-white transition-colors" href="#hero">Home</a></li>
<li><a class="hover:text-white transition-colors" href="#features">About</a></li>
<li><a class="hover:text-white transition-colors" href="#features">Product</a></li>
<li><a class="hover:text-white transition-colors" href="#pricing">Pricing</a></li>
<li><a class="hover:text-white transition-colors" href="#demo">Contact</a></li>
</ul>
</div>
<!-- Links Column 2 -->
<div class="md:col-span-3 space-y-2.5">
<h4 class="font-semibold text-white uppercase tracking-wider text-[11px]">Legal &amp; Docs</h4>
<ul class="space-y-2 text-brand-muted">
<li><a class="hover:text-white transition-colors" href="#">FERPA &amp; COPPA</a></li>
<li><a class="hover:text-white transition-colors" href="#">Privacy Policy</a></li>
<li><a class="hover:text-white transition-colors" href="#">Terms of Service</a></li>
<li><a class="hover:text-white transition-colors" href="#">Security Center</a></li>
</ul>
</div>
</div>
<!-- Bottom Copyright & Policies -->
<div class="max-w-7xl mx-auto px-6 mt-12 pt-6 flex flex-col sm:flex-row justify-between items-center text-[11px] text-brand-muted border-t border-white/5 relative z-10">
<div>© 2025 GeliScope Inc. All rights reserved.</div>
<div class="flex space-x-6 mt-4 sm:mt-0">
<a class="hover:text-white transition-colors" href="#">Terms &amp; Conditions</a>
<a class="hover:text-white transition-colors" href="#">Privacy Policy</a>
<a class="hover:text-white transition-colors" href="#">Cookie Policy</a>
</div>
</div>
<!-- Large Watermarked Brand Name across bottom background (IMAGE_2 Style) -->
<div class="w-full text-center pointer-events-none select-none -mt-8 overflow-hidden opacity-[0.035] leading-none font-heading font-extrabold text-[18vw] text-white">
        GeliScope
      </div>
</section>
<!-- END: FooterCallToAction -->
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
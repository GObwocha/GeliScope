<script setup>
const currentYear = new Date().getFullYear();
const scrollToSection = (hash) => {
  const el = document.querySelector(hash)
  if (el) {
    el.scrollIntoView({ behavior: 'smooth' })
  }
}
</script>

<template>
  <v-footer class="aurora-footer text-white d-flex flex-column pb-0 px-0 mt-16 position-relative overflow-hidden border-t" color="transparent" id="demo">
    
    <!-- Center Glowing Sphere CTA -->
    <v-container class="pt-16 pb-12 text-center position-relative z-10 max-w-3xl">
      <div class="d-flex flex-column align-center justify-center">
        <div class="glow-orb mb-8 animate-bounce transition-transform"></div>
        <h2 class="text-h2 font-weight-black text-white font-heading mb-4 tracking-tight">
          Let's talk
        </h2>
        <p class="text-medium-emphasis text-body-1 max-w-md mx-auto mb-8 lh-relaxed">
          Launch your AI educational intelligence assistant and elevate the modern classroom today.
        </p>
        <v-btn 
          color="white" 
          variant="flat" 
          class="rounded-pill text-uppercase font-weight-bold demo-btn" 
          size="x-large"
          href="#demo"
        >
          Book a Demo
        </v-btn>
      </div>
    </v-container>

    <!-- Links Grid -->
    <v-container class="mt-8 pt-12 border-t max-w-7xl position-relative z-10">
      <v-row>
        <!-- Left Brand Description -->
        <v-col cols="12" md="6" class="mb-8 mb-md-0">
          <div class="d-flex align-center gap-2 mb-4">
            <span class="text-h6 font-weight-black text-white font-heading">
              Geli<span class="text-secondary">Scope</span>
            </span>
          </div>
          <p class="text-medium-emphasis text-body-2 max-w-sm lh-relaxed">
            Your 24/7 educational intelligence platform — ready to empower students, inform parents, and elevate teachers.
          </p>
        </v-col>

        <!-- Links Column 1 -->
        <v-col cols="6" md="3">
          <div class="text-caption font-weight-bold text-white text-uppercase tracking-wider mb-4">Company</div>
          <ul class="list-unstyled text-medium-emphasis text-body-2 space-y-2">
            <li><a class="footer-link" @click.prevent="scrollToSection('#hero')">Home</a></li>
            <li><a class="footer-link" @click.prevent="scrollToSection('#features')">About</a></li>
            <li><a class="footer-link" @click.prevent="scrollToSection('#features')">Product</a></li>
            <li><a class="footer-link " @click.prevent="scrollToSection('#pricing')">Pricing</a></li>
            <li><a class="footer-link" @click.prevent="scrollToSection('#demo')">Contact</a></li>
          </ul>
        </v-col>

        <!-- Links Column 2 -->
        <v-col cols="6" md="3">
          <div class="text-caption font-weight-bold text-white text-uppercase tracking-wider mb-4">Legal & Docs</div>
          <ul class="list-unstyled text-medium-emphasis text-body-2 space-y-2">
            <li><a class="footer-link" href="#">FERPA & COPPA</a></li>
            <li><a class="footer-link" href="#">Privacy Policy</a></li>
            <li><a class="footer-link" href="#">Terms of Service</a></li>
            <li><a class="footer-link" href="#">Security Center</a></li>
          </ul>
        </v-col>
      </v-row>
    </v-container>

    <!-- Bottom Copyright & Policies -->
    <v-container class="pt-6 pb-6 mt-4 border-t max-w-7xl position-relative z-10 text-caption text-medium-emphasis d-flex flex-column flex-sm-row justify-space-between align-center">
      <div>&copy; {{ currentYear }} GeliScope Inc. All rights reserved.</div>
      <div class="d-flex gap-6 mt-4 mt-sm-0">
        <a class="footer-link" href="#">Terms & Conditions</a>
        <a class="footer-link" href="#">Privacy Policy</a>
        <a class="footer-link" href="#">Cookie Policy</a>
      </div>
    </v-container>

    <!-- Large Watermarked Brand Name -->
    <div class="watermark-text font-heading font-weight-black text-center text-white">
      GeliScope
    </div>
  </v-footer>
</template>

<style scoped>
.border-t { border-top: 1px solid rgba(255, 255, 255, 0.05) !important; }
.max-w-7xl { max-width: 1280px; margin: 0 auto; }
.max-w-3xl { max-width: 768px; margin: 0 auto; }
.max-w-md { max-width: 448px; }
.max-w-sm { max-width: 384px; }
.lh-relaxed { line-height: 1.625; }
.tracking-wider { letter-spacing: 0.05em; }
.tracking-tight { letter-spacing: -0.025em; }
.gap-2 { gap: 8px; }
.gap-6 { gap: 24px; }
.space-y-2 > li { margin-bottom: 8px; }
.list-unstyled { list-style-type: none; padding-left: 0; }

.footer-link {
  color: inherit;
  text-decoration: none;
  transition: color 0.2s ease;
  cursor: pointer;
}
.footer-link:hover {
  color: white;
}

.demo-btn {
  letter-spacing: 1px;
  color: #0B1120 !important;
  transition: all 0.3s ease;
}
.demo-btn:hover {
  background-color: #f1f5f9 !important;
  box-shadow: 0 10px 15px -3px rgba(34, 211, 238, 0.2);
  transform: scale(1.05);
}

.aurora-footer {
  background: radial-gradient(circle at 50% 25%, rgba(34, 211, 238, 0.25) 0%, rgba(139, 92, 246, 0.35) 30%, rgba(11, 17, 32, 0.95) 75%) !important;
}

.glow-orb {
  width: 56px;
  height: 56px;
  border-radius: 50%;
  background: radial-gradient(circle at 35% 35%, #67e8f9 0%, #818cf8 50%, #4338ca 100%);
  box-shadow: 0 0 45px rgba(34, 211, 238, 0.55), inset 0 0 15px rgba(255, 255, 255, 0.6);
}

.animate-bounce {
  animation: bounce 2s infinite;
}
@keyframes bounce {
  0%, 100% { transform: translateY(-25%); animation-timing-function: cubic-bezier(0.8,0,1,1); }
  50% { transform: none; animation-timing-function: cubic-bezier(0,0,0.2,1); }
}

.watermark-text {
  pointer-events: none;
  user-select: none;
  margin-top: -32px;
  opacity: 0.035;
  line-height: 1;
  font-size: 18vw;
  width: 100%;
}
</style>
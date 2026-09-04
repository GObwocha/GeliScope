<template>
  <v-app-bar 
    fixed 
    app 
    elevation="0" 
    class="backdrop-blur border-b"
    color="rgba(11, 17, 32, 0.7)"
  >
    <v-container class="d-flex align-center justify-space-between max-w-7xl mx-auto px-6 h-100">
      <!-- Logo Branding -->
      <a class="d-flex align-center gap-2 text-decoration-none group" href="#">
        <div class="logo-box">
          <div class="logo-inner d-flex align-center justify-center">
            <v-icon color="secondary" size="small">mdi-lightning-bolt</v-icon>
          </div>
        </div>
        <span class="text-h6 font-weight-black text-white ml-2">Geli<span class="text-secondary">Scope</span></span>
      </a>

      <!-- Desktop Navigation Menu -->
      <div class="d-none d-md-flex align-center gap-6 text-body-2 font-weight-medium text-medium-emphasis">
        <a class="nav-link" href="#hero">Home</a>
        <a class="nav-link" href="#features">Features</a>
        <a class="nav-link" href="#pricing">Pricing</a>
        <a class="nav-link" href="#testimonials">Testimonials</a>
        <a class="nav-link" href="#faq">FAQ</a>
      </div>

      <!-- Action Buttons -->
      <div class="d-flex align-center gap-4">
        <v-btn variant="text" class="text-none font-weight-medium text-medium-emphasis" to="/login">Sign In</v-btn>
        <v-btn 
          color="white" 
          variant="flat" 
          class="rounded-pill text-uppercase font-weight-bold demo-btn" 
          size="small"
        >
          Book a Demo
        </v-btn>
      </div>
    </v-container>
  </v-app-bar>
</template>

<style scoped>
.backdrop-blur {
  backdrop-filter: blur(24px);
}
.border-b {
  border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
}
.max-w-7xl {
  max-width: 1280px;
}
.gap-2 { gap: 10px; }
.gap-4 { gap: 16px; }
.gap-6 { gap: 36px; }

.logo-box {
  width: 36px;
  height: 36px;
  border-radius: 12px;
  background: linear-gradient(to top right, #8B5CF6, #22D3EE);
  padding: 2px;
  box-shadow: 0 10px 15px -3px rgba(34, 211, 238, 0.2);
  transition: transform 0.3s ease;
}
.group:hover .logo-box {
  transform: scale(1.05);
}
.logo-inner {
  width: 100%;
  height: 100%;
  background-color: #0B1120;
  border-radius: 10px;
}

.nav-link {
  color: inherit;
  text-decoration: none;
  transition: color 0.2s ease;
}
.nav-link:hover {
  color: white;
}

.demo-btn {
  letter-spacing: 1px;
  transition: all 0.3s ease;
}
.demo-btn:hover {
  background-color: #f1f5f9 !important;
  box-shadow: 0 10px 15px -3px rgba(255, 255, 255, 0.2);
  transform: scale(1.02);
}
.demo-btn:active {
  transform: scale(0.98);
}
</style>

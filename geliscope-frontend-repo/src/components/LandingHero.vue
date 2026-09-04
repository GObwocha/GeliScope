<template>
  <v-sheet class="position-relative d-flex flex-column justify-center align-center min-h-screen px-4 px-sm-6 overflow-hidden aurora-bg " id="hero">
    <!-- Glow ambient light bar atop the v-sheet -->
    <div class="ambient-glow"></div>
    
    <!-- Hero Header & Tag -->
    <div class="max-w-4xl mx-auto text-center mt-12 mb-12">
      <!-- Floating Pill Badge -->
      <div class="pill-badge d-inline-flex align-center gap-2 px-4 py-2 rounded-pill border shadow-inner mb-6">
        <span class="dot bg-secondary animate-ping"></span>
        <span class="text-caption text-uppercase font-weight-bold text-accent tracking-widest">Next-Gen Educational AI</span>
      </div>
      
      <!-- Main Headline -->
      <h1 class="text-h3 sm-text-h2 md-text-h1 font-weight-black text-white lh-tight mb-6 tracking-tight">
        AI Educational Intelligence <br class="d-none d-sm-inline"/>
        <span class="text-gradient">That Never Sleeps</span>
      </h1>
      
      <!-- Subheadline text -->
      <p class="text-body-1 text-medium-emphasis max-w-2xl mx-auto lh-relaxed mb-8">
        Empowering students with 24/7 personalized tutoring, keeping parents seamlessly informed, and liberating teachers with autonomous grading & curriculum insights.
      </p>
      
      <!-- CTA Trigger Button -->
      <v-btn 
        color="white" 
        variant="flat" 
        class="rounded-pill text-uppercase font-weight-bold demo-btn" 
        size="x-large"
        to="/register"
      >
        Get Started
      </v-btn>
    </div>

    <!-- Interactive AI Chat Interface Preview Window -->
    <div class="w-100 max-w-3xl mt-12 px-2">
      <div class="chat-wrapper rounded-xl p-0.5 shadow-xl">
        <v-card class="chat-inner rounded-lg pa-5 pa-sm-7 bg-surface" elevation="0">
          
          <!-- Window Title bar / Status -->
          <div class="d-flex align-center justify-space-between border-b pb-3 mb-5">
            <div class="d-flex align-center gap-2">
              <span class="dot-sm bg-secondary animate-pulse"></span>
              <span class="text-caption font-mono tracking-wider text-medium-emphasis text-uppercase">AI Learning Session • Live 24/7</span>
            </div>
            <div class="d-flex gap-1">
              <span class="dot-sm bg-white opacity-10"></span>
              <span class="dot-sm bg-white opacity-10"></span>
              <span class="dot-sm bg-white opacity-10"></span>
            </div>
          </div>
          
          <!-- Greeting Prompt Message Display -->
          <div class="d-flex align-start gap-3 text-left mb-6 min-h-msg">
            <v-avatar class="avatar-gradient shadow-md" size="32">
              <span class="text-caption font-weight-bold text-white">G</span>
            </v-avatar>
            <div class="msg-bubble rounded-xl rounded-ts-sm px-4 py-3">
              <p class="text-body-2 sm-text-body-1 text-white font-weight-medium mb-1">
                Hi 👋 How can <span class="text-secondary font-weight-bold">GeliScope</span> assist your learning today?
              </p>
              <p class="text-caption text-medium-emphasis">
                Suggested: Explain Quantum Physics basics, review my calculus proof, or summarize AP History Chapter 4.
              </p>
            </div>
          </div>
          
          <!-- Chat Mock Console Input Field -->
          <div class="input-console d-flex align-center rounded-xl border px-4 py-3 transition-colors">
            <v-btn icon variant="text" density="comfortable" class="text-medium-emphasis mr-2">
              <v-icon>mdi-paperclip</v-icon>
            </v-btn>
            <input 
              type="text" 
              class="flex-grow-1 bg-transparent border-0 text-white text-body-2 outline-none" 
              placeholder="Ask any question, paste homework problems, or request a syllabus rubric..." 
            />
            <div class="d-flex align-center gap-2 pl-3 border-l text-medium-emphasis">
              <v-btn icon variant="text" density="comfortable" color="secondary" class="opacity-80">
                <v-icon>mdi-microphone</v-icon>
              </v-btn>
              <v-btn icon variant="text" density="comfortable" color="secondary">
                <v-icon class="hover-scale">mdi-send</v-icon>
              </v-btn>
            </div>
          </div>
          
        </v-card>
      </div>
    </div>

    <!-- Partner Logo Grid -->
    <div class="w-100 max-w-5xl mt-16 pt-8 border-t d-flex flex-wrap align-center justify-space-around gap-6 text-medium-emphasis opacity-70">
      <div class="d-flex align-center gap-2 font-mono text-caption tracking-wider">
        <span class="dot-sm bg-primary"></span>
        <span>OXFORD STEM</span>
      </div>
      <div class="d-flex align-center gap-2 font-mono text-caption tracking-wider">
        <span class="dot-sm bg-error"></span>
        <span>CAMBRIDGE AI</span>
      </div>
      <div class="d-flex align-center gap-2 font-mono text-caption tracking-wider">
        <span class="dot-sm bg-secondary"></span>
        <span>HARVARD EDTECH</span>
      </div>
      <div class="d-flex align-center gap-2 font-mono text-caption tracking-wider">
        <span class="dot-sm bg-warning"></span>
        <span>STANFORD LABS</span>
      </div>
      <div class="d-flex align-center gap-2 font-mono text-caption tracking-wider">
        <span class="dot-sm bg-info"></span>
        <span>MIT HORIZON</span>
      </div>
    </div>
  </v-sheet>
</template>

<style scoped>
.min-h-screen { min-height: 100vh; }
.max-w-5xl { max-width: 1024px; }
.max-w-4xl { max-width: 896px; }
.max-w-3xl { max-width: 768px; }
.max-w-2xl { max-width: 672px; }
.w-100 { width: 100%; }

.gap-1 { gap: 4px; }
.gap-2 { gap: 8px; }
.gap-3 { gap: 12px; }
.gap-6 { gap: 24px; }

.lh-tight { line-height: 1.12; }
.lh-relaxed { line-height: 1.625; }
.tracking-widest { letter-spacing: 0.1em; }
.tracking-tight { letter-spacing: -0.025em; }

.border { border: 1px solid rgba(255, 255, 255, 0.1); }
.border-b { border-bottom: 1px solid rgba(255, 255, 255, 0.05); }
.border-t { border-top: 1px solid rgba(255, 255, 255, 0.05); }
.border-l { border-left: 1px solid rgba(255, 255, 255, 0.1); }
.opacity-10 { opacity: 0.1; }

.ambient-glow {
  position: absolute;
  top: 80px; left: 50%;
  transform: translateX(-50%);
  width: 75%; max-width: 896px; height: 288px;
  background: linear-gradient(to bottom, rgba(34, 211, 238, 0.25), rgba(139, 92, 246, 0.2), transparent);
  filter: blur(64px);
  pointer-events: none;
  z-index: 0;
}

.pill-badge {
  background: rgba(255, 255, 255, 0.05);
  backdrop-filter: blur(12px);
  box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.06);
}

.dot { width: 8px; height: 8px; border-radius: 50%; }
.dot-sm { width: 10px; height: 10px; border-radius: 50%; display: inline-block; }

.animate-ping { animation: ping 1s cubic-bezier(0, 0, 0.2, 1) infinite; }
@keyframes ping { 75%, 100% { transform: scale(2); opacity: 0; } }
.animate-pulse { animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
@keyframes pulse { 50% { opacity: .5; } }

.text-gradient {
  background: linear-gradient(to right, #ffffff, #f1f5f9, #22D3EE);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.demo-btn {
  letter-spacing: 1px;
  color: #0B1120 !important;
  box-shadow: 0 0 30px rgba(255, 255, 255, 0.35);
  transition: all 0.3s ease;
}
.demo-btn:hover {
  box-shadow: 0 0 40px rgba(34, 211, 238, 0.5);
  transform: scale(1.05);
}

.chat-wrapper {
  background: linear-gradient(to right, rgba(34, 211, 238, 0.7), rgba(139, 92, 246, 0.5), rgba(34, 211, 238, 0.6));
  box-shadow: 0 10px 60px rgba(34, 211, 238, 0.18);
  padding: 1.5px;
}
.chat-inner {
  background-color: #0e1628 !important;
}

.avatar-gradient {
  background: linear-gradient(to top right, #8B5CF6, #22D3EE);
  box-shadow: 0 4px 6px -1px rgba(139, 92, 246, 0.3);
}

.msg-bubble {
  background: rgba(255, 255, 255, 0.05);
  border: 1px solid rgba(255, 255, 255, 0.1);
  max-width: 36rem;
  box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
}
.rounded-ts-sm {
  border-top-left-radius: 4px !important;
}
.min-h-msg {
  min-height: 110px;
}

.input-console {
  background-color: rgba(15, 23, 42, 0.9);
}
.input-console:focus-within {
  border-color: rgba(34, 211, 238, 0.6) !important;
}
.outline-none:focus {
  outline: 2px solid transparent;
  outline-offset: 2px;
}
.hover-scale {
  transition: transform 0.2s;
}
.hover-scale:hover {
  transform: scale(1.1);
}
.aurora-bg {
  background: 
    radial-gradient(ellipse 65% 55% at 50% -10%, rgba(34, 211, 238, 0.22) 0%, rgba(139, 92, 246, 0.28) 45%, rgba(11, 17, 32, 0) 100%),
    repeating-linear-gradient(90deg, rgba(255, 255, 255, 0.015) 0px, rgba(255, 255, 255, 0.015) 2px, transparent 2px, transparent 64px);
}
</style>

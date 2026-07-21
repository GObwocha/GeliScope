<script setup>
import { ref } from 'vue'

const activeTab = ref('parents')
</script>

<template>
  <div class="landing-page position-relative w-100 overflow-hidden pb-16">
    
    <!-- Hero Section -->
    <v-container class="pt-16 mt-16 text-center position-relative z-10" style="max-width: 1000px;">
      <v-row justify="center">
        <v-col cols="12" class="d-flex flex-column align-center">
          <!-- Small badge -->
          <v-chip color="secondary" variant="outlined" class="mb-8 font-weight-bold px-4 py-4 text-subtitle-2 border-opacity-50" style="border-radius: 100px; background: rgba(81, 250, 170, 0.05);">
            <v-icon start color="secondary" size="small">mdi-star-four-points</v-icon>
            AI Vision Assessment Engine
          </v-chip>
          
          <h1 class="text-h2 font-weight-black tracking-tight mb-4 leading-tight text-white" style="font-size: 4rem !important; line-height: 1.1 !important;">
            Educational Analytics That <br />
            <span class="text-gradient">Exceeds Expectations</span>
          </h1>
          
          <p class="text-h6 text-muted font-weight-light mb-10 leading-relaxed mt-4 max-w-xl">
            GeliScope eliminates the guesswork in education. Using advanced AI Vision OCR and our Dual-Database engine, we empower parents, students, and teachers.
          </p>
          
          <div class="d-flex flex-wrap justify-center gap-4">
            <v-btn color="secondary" size="x-large" to="/register" class="pill-btn px-10 py-4 text-background shadow-glow">
              Get started for free
            </v-btn>
            <v-btn variant="outlined" color="muted" size="x-large" to="/how-it-works" prepend-icon="mdi-play-circle-outline" class="pill-btn px-8 py-4 text-white border-opacity-50 hover:border-opacity-100 transition-all">
              See how it works
            </v-btn>
          </div>
        </v-col>
      </v-row>
    </v-container>

    <!-- Bento Grid Section -->
    <v-container class="mt-16 position-relative z-10">
      <v-row>
        <!-- Top Left: Results in 5 minutes -->
        <v-col cols="12" md="4">
          <div class="bento-card h-100 p-8 rounded-xl d-flex flex-column justify-space-between pa-8">
            <div>
              <h3 class="text-h5 font-weight-bold text-white mb-2">Results In <span class="text-secondary">5 Minutes</span></h3>
              <p class="text-muted text-body-2 mb-6">Not 5 days. Scan exam papers and instantly get actionable AI insights.</p>
            </div>
            
            <div class="bg-background rounded-lg pa-4 border border-opacity-10 mb-4 d-flex align-center justify-space-between">
              <div class="d-flex align-center">
                <v-icon color="secondary" size="large" class="mr-3">mdi-check-circle</v-icon>
                <div>
                  <div class="text-body-2 font-weight-bold text-white">With GeliScope</div>
                  <div class="text-caption text-secondary">Instant AI OCR</div>
                </div>
              </div>
              <div class="text-h6 font-weight-bold text-white">1 - 5 Min</div>
            </div>

            <div class="bg-background rounded-lg pa-4 border border-opacity-10 opacity-60 d-flex align-center justify-space-between">
              <div class="d-flex align-center">
                <v-icon color="muted" size="large" class="mr-3">mdi-timer-sand</v-icon>
                <div>
                  <div class="text-body-2 font-weight-bold text-muted">Without GeliScope</div>
                  <div class="text-caption text-muted">Manual Marking</div>
                </div>
              </div>
              <div class="text-body-1 font-weight-bold text-muted">5 Days</div>
            </div>
          </div>
        </v-col>

        <!-- Top Right: Dual Database -->
        <v-col cols="12" md="8">
          <div class="bento-card h-100 p-8 rounded-xl pa-8 d-flex flex-column position-relative overflow-hidden">
            <div class="glow-blur-purple" style="top: -50px; right: -50px; opacity: 0.5;"></div>
            
            <h3 class="text-h4 font-weight-bold text-white mb-2 position-relative z-10">Get accurate insights, <span class="text-gradient">faster</span></h3>
            <p class="text-muted text-body-1 mb-8 position-relative z-10">Dual-Database Sync matches structured scores with unstructured Bloom's taxonomy mapping.</p>
            
            <v-row class="position-relative z-10 flex-grow-1">
              <v-col cols="12" sm="6" class="d-flex flex-column justify-center">
                <div class="d-flex align-center mb-6">
                  <div class="rounded-circle d-flex align-center justify-center mr-4" style="width: 60px; height: 60px; border: 4px solid #51FAAA; border-left-color: rgba(81,250,170,0.2);">
                    <span class="text-h6 font-weight-bold text-secondary">98%</span>
                  </div>
                  <div>
                    <div class="text-body-1 font-weight-bold text-white">Diagnostic Accuracy</div>
                    <div class="text-caption text-muted">Verified against Kenyan curriculum</div>
                  </div>
                </div>
                
                <div class="d-flex align-center">
                  <div class="rounded-circle d-flex align-center justify-center mr-4" style="width: 60px; height: 60px; border: 4px solid #AB7DE3; border-top-color: rgba(171,125,227,0.2);">
                    <span class="text-h6 font-weight-bold text-primary">100%</span>
                  </div>
                  <div>
                    <div class="text-body-1 font-weight-bold text-white">Automated Rubrics</div>
                    <div class="text-caption text-muted">Configured instantly by teachers</div>
                  </div>
                </div>
              </v-col>
              <v-col cols="12" sm="6" class="d-flex align-center justify-center">
                <!-- Visual mock of a dashboard card -->
                <div class="w-100 rounded-lg bg-background pa-6 border border-opacity-10 shadow-glow">
                  <div class="d-flex justify-space-between align-center mb-4">
                    <span class="text-caption text-muted font-weight-bold text-uppercase">Form 1 Performance</span>
                    <v-chip size="x-small" color="secondary" variant="flat">Live</v-chip>
                  </div>
                  <div class="d-flex align-end gap-2 mb-2" style="height: 100px;">
                    <div class="bg-primary rounded-t-sm w-100 transition-all" style="height: 40%"></div>
                    <div class="bg-muted rounded-t-sm w-100 transition-all" style="height: 60%"></div>
                    <div class="bg-primary rounded-t-sm w-100 transition-all" style="height: 30%"></div>
                    <div class="bg-secondary rounded-t-sm w-100 transition-all shadow-glow" style="height: 90%"></div>
                    <div class="bg-primary rounded-t-sm w-100 transition-all" style="height: 50%"></div>
                    <div class="bg-muted rounded-t-sm w-100 transition-all" style="height: 70%"></div>
                  </div>
                  <div class="text-center text-caption text-muted mt-2">Class Average Trend</div>
                </div>
              </v-col>
            </v-row>
          </div>
        </v-col>
      </v-row>

      <v-row>
        <!-- Bottom Full Width: Feature showcase -->
        <v-col cols="12">
          <div class="bento-card rounded-xl pa-8 text-center position-relative overflow-hidden mt-4">
             <div class="glow-blur-green" style="bottom: -150px; left: 50%; transform: translateX(-50%); opacity: 0.3;"></div>
             
             <v-chip color="primary" variant="outlined" class="mb-6 font-weight-bold border-opacity-50" style="border-radius: 100px; background: rgba(171, 125, 227, 0.05);">
                Targeted Solutions
             </v-chip>
             
             <h3 class="text-h3 font-weight-bold text-white mb-10 position-relative z-10">Everything financial I need<br/> <span class="text-muted">Wait, no—everything <span class="text-secondary">educational</span> I need.</span></h3>
             
             <div class="d-flex justify-center gap-4 mb-8 position-relative z-10 flex-wrap">
               <v-btn class="pill-btn px-6 text-white" :color="activeTab === 'parents' ? 'primary' : 'background'" :variant="activeTab === 'parents' ? 'flat' : 'outlined'" @click="activeTab = 'parents'">For Parents</v-btn>
               <v-btn class="pill-btn px-6 text-white" :color="activeTab === 'students' ? 'accent' : 'background'" :variant="activeTab === 'students' ? 'flat' : 'outlined'" @click="activeTab = 'students'">For Students</v-btn>
               <v-btn class="pill-btn px-6 text-white" :color="activeTab === 'teachers' ? 'secondary' : 'background'" :variant="activeTab === 'teachers' ? 'flat' : 'outlined'" @click="activeTab = 'teachers'">For Teachers</v-btn>
             </div>
             
             <div class="bg-background rounded-xl pa-8 text-left border border-opacity-10 position-relative z-10 max-w-4xl mx-auto min-h-[200px]">
                <transition name="fade" mode="out-in">
                  <div v-if="activeTab === 'parents'" key="parents">
                    <h4 class="text-h5 font-weight-bold text-primary mb-2">Deep Diagnostic Insights</h4>
                    <p class="text-muted text-body-1 mb-4">No more generic report cards ("Math: C+"). Know exactly which sub-topics and Bloom's taxonomy skills your child needs help with.</p>
                    <div class="d-flex align-center text-white"><v-icon color="primary" class="mr-2">mdi-check</v-icon> Direct WhatsApp/SMS integration alerts</div>
                  </div>
                  <div v-else-if="activeTab === 'students'" key="students">
                    <h4 class="text-h5 font-weight-bold text-accent mb-2">Self-Guided Exam Evaluation</h4>
                    <p class="text-muted text-body-1 mb-4">Overcome teacher unavailability. Scan your exam paper and let AI guide your revision step-by-step.</p>
                    <div class="d-flex align-center text-white"><v-icon color="accent" class="mr-2">mdi-check</v-icon> Instant AI Vision OCR analysis</div>
                  </div>
                  <div v-else key="teachers">
                    <h4 class="text-h5 font-weight-bold text-secondary mb-2">Automated Rubrics & Analytics</h4>
                    <p class="text-muted text-body-1 mb-4">Reduce marking burnout and identify class-wide conceptual bottlenecks before national examinations.</p>
                    <div class="d-flex align-center text-white"><v-icon color="secondary" class="mr-2">mdi-check</v-icon> Real-time academic leaderboard tracking</div>
                  </div>
                </transition>
             </div>
          </div>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<style scoped>
.max-w-xl { max-width: 600px; margin-left: auto; margin-right: auto; }
.max-w-4xl { max-width: 900px; }
.min-h-\[200px\] { min-height: 200px; }
.z-10 { z-index: 10; }
</style>

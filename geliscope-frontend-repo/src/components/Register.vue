<script setup>
import { ref } from 'vue'
import RegisterAsStudent from '@/components/RegisterAsStudent.vue'
import RegisterAsTutor from '@/components/RegisterAsTutor.vue'
import RegisterAsParent from '@/components/RegisterAsParent.vue'

const activeRole = ref('student')

// Map roles to components
const roleComponents = {
 student: RegisterAsStudent,
 teacher: RegisterAsTutor,
 parent: RegisterAsParent
}
</script>

<template>
<div class="register-page-wrapper w-100 h-100 position-relative">
  <v-btn 
    prepend-icon="mdi-arrow-left" 
    variant="text" 
    color="medium-emphasis" 
    to="/" 
    class="position-absolute text-none font-weight-medium" 
    style="top: 24px; left: 24px; z-index: 10;"
  >Back to website</v-btn>
   <v-container fluid class="pa-0 min-vh-100 font-family-sans bg-background">
 <v-row no-gutters class="min-vh-100">
 
 <!-- Left Side Branding (Hidden on mobile) -->
 <v-col cols="12" md="5" class="bg-surface d-none d-md-flex flex-column justify-center align-center px-12 border-e">
 <div class="text-center max-w-md">
 <v-icon size="80" color="primary" class="mb-6">mdi-school-outline</v-icon>
 <h1 class="text-h3 font-weight-black mb-4">GeliScope</h1>
 <p class="text-h6 text-medium-emphasis font-weight-regular lh-tight">
 Join thousands of users tracking academic progress and reaching their full potential.
 </p>
 </div>
 </v-col>

 <!-- Right Side Form Area -->
 <v-col cols="12" md="7" class="d-flex flex-column justify-center px-6 px-md-16 py-12 eoverflow-y: auto;">
 <div class="max-w-lg mx-auto w-100 my-auto">
 <div class="mb-6 text-center text-md-left">
 <h2 class="text-h4 font-weight-bold mb-2">Create Account</h2>
 <p class="text-body-1 text-medium-emphasis">Select your role and enter your details below.</p>
 </div>

 <!-- Role Selection Buttons -->
 <div class="d-flex flex-wrap gap-4 mb-8">
 <v-btn 
 variant="tonal" 
 class="flex-grow-1 text-none font-weight-bold rounded-lg"
 :color="activeRole === 'student' ? 'primary' : 'default'"
 @click="activeRole = 'student'"
 size="large"
 >
 <v-icon start>mdi-account-school-outline</v-icon> Student
 </v-btn>
 <v-btn 
 variant="tonal" 
 class="flex-grow-1 text-none font-weight-bold rounded-lg"
 :color="activeRole === 'teacher' ? 'primary' : 'default'"
 @click="activeRole = 'teacher'"
 size="large"
 >
 <v-icon start>mdi-account-tie</v-icon> Teacher
 </v-btn>
 <v-btn 
 variant="tonal" 
 class="flex-grow-1 text-none font-weight-bold rounded-lg"
 :color="activeRole === 'parent' ? 'primary' : 'default'"
 @click="activeRole = 'parent'"
 size="large"
 >
 <v-icon start>mdi-account-child-outline</v-icon> Parent
 </v-btn>
 </div>

 <!-- Google Auth Button -->
 <v-btn 
 block 
 variant="outlined" 
 size="large" 
 class="mb-6 rounded-lg font-weight-bold text-none border-medium"
 prepend-icon="mdi-google"
 >
 Sign up with Google
 </v-btn>

 <div class="d-flex align-center mb-6">
 <v-divider></v-divider>
 <span class="text-medium-emphasis font-weight-bold text-uppercase">Or sign up with email</span>
 <v-divider></v-divider>
 </div>

 <!-- Dynamic Component Rendering -->
 <component :is="roleComponents[activeRole]" />

 <div class="text-center mt-6 text-body-2 text-medium-emphasis">
 Already have an account? 
 <router-link to="/login" class="text-primary font-weight-bold text-decoration-none">Sign in</router-link>
 </div>
 </div>
 </v-col>

 </v-row>
 </v-container>
 </div>
</template>

<style scoped>
.extracted-style-1 {
 max-height: 100vh; overflow-y: auto;
}

.min-vh-100 {
 min-height: 100vh;
}
.max-w-md {
 max-width: 400px;
}
.max-w-lg {
 max-width: 500px;
}
.lh-tight {
 line-height: 1.3 !important;
}
.border-e {
 border-right: 1px solid rgba(var(--v-border-color), 0.1);
}
.gap-4 {
 gap: 16px;
}
.font-family-sans {
 font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}
</style>

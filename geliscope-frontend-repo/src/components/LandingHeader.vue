<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { useTheme } from 'vuetify'

const router = useRouter()
const authStore = useAuthStore()
const theme = useTheme()
const drawer = ref(false)

const toggleTheme = () => {
  theme.global.name.value = theme.global.current.value.dark ? 'light' : 'dark'
}

const handleLogout = async () => {
  await authStore.logout()
  router.push('/login')
}

// Navigation links for the landing page (using anchor tags for smooth scrolling)
const navItems = [
  { title: 'Product', to: '#hero' },
  { title: 'Features', to: '#features' },
  { title: 'Testimonials', to: '#testimonials' },
  { title: 'Pricing', to: '#pricing' },
  { title: 'FAQ', to: '#faq' }
]

const scrollToSection = (hash) => {
  const el = document.querySelector(hash)
  if (el) {
    el.scrollIntoView({ behavior: 'smooth' })
  }
}
</script>

<template>
  <!-- Public App Bar (Modern Floating Pill from App.vue) -->
  <v-app-bar color="transparent" elevation="0" height="100" style="position: fixed;" scroll-behavior="collapse">
    <v-card class="mx-auto rounded-pill d-flex align-center px-4 px-md-8 w-100 border-opacity-10" max-width="1100" height="70" elevation="8">
      
      <!-- Mobile menu toggle -->
      <v-app-bar-nav-icon @click="drawer = !drawer" class="hidden-md-and-up"></v-app-bar-nav-icon>
      
      <!-- Logo Area -->
      <div class="d-flex align-center cursor-pointer" @click="scrollToSection('#hero')">
        <v-avatar color="secondary" size="40" class="mr-3" rounded="lg">
          <v-icon color="background">mdi-shield-check</v-icon>
        </v-avatar>
        <div class="text-h6 font-weight-bold d-flex align-center">
          GeliScope 
        </div>
      </div>

      <v-spacer></v-spacer>

      <!-- Centered Links (Desktop) -->
      <div class="hidden-sm-and-down d-flex gap-6">
        <a 
          v-for="item in navItems" 
          :key="item.title" 
          :href="item.to" 
          @click.prevent="scrollToSection(item.to)"
          class="text-decoration-none text-muted font-weight-medium px-4 py-2 transition-fast-in-fast-out hover:text-primary" 
          style="transition: color 0.3s;"
        >
          {{ item.title }}
        </a>
      </div>

      <v-spacer class="hidden-sm-and-down"></v-spacer>

      <!-- Right Side Auth Actions -->
      <template v-if="authStore.isAuthenticated">
        <div class="hidden-sm-and-down d-flex align-center gap-2">
          <v-btn color="secondary" variant="flat" to="/dashboard" class="pill-btn px-6 mx-2">Dashboard</v-btn>
          <v-btn icon @click="toggleTheme" variant="text" class="ml-2">
            <v-icon>{{ theme.global.current.value.dark ? 'mdi-white-balance-sunny' : 'mdi-moon-waning-crescent' }}</v-icon>
          </v-btn>
          <v-btn color="error" variant="flat" @click="handleLogout" class="pill-btn ml-2">Log Out</v-btn>
        </div>
      </template>
      <template v-else>
        <div class="hidden-sm-and-down d-flex align-center">
          <v-btn icon @click="toggleTheme" variant="text" class="mx-2">
            <v-icon>{{ theme.global.current.value.dark ? 'mdi-white-balance-sunny' : 'mdi-moon-waning-crescent' }}</v-icon>
          </v-btn>
          <v-btn variant="outlined" color="muted" class="pill-btn px-6 border-opacity-50 mx-2" to="/login">Log In</v-btn>
          <v-btn color="secondary" variant="flat" class="pill-btn px-6 font-weight-bold" to="/register">Get Started</v-btn>
        </div>
      </template>
    </v-card>
  </v-app-bar>

  <!-- Mobile Navigation Drawer -->
  <v-navigation-drawer 
    v-model="drawer" 
    temporary 
    class="bg-cardBg border-r border-opacity-10"
    width="280"
    style="height: 100vh; position: fixed; top: 0; left: 0; z-index: 9999;"
  >
    <div class="pa-4 d-flex align-center border-b border-opacity-10 cursor-pointer" @click="scrollToSection('#hero'); drawer = false">
      <v-avatar color="secondary" size="36" class="mr-3" rounded="lg">
        <v-icon color="background" size="small">mdi-shield-check</v-icon>
      </v-avatar>
      <span class="font-weight-black text-h5 tracking-tight">GeliScope</span>
    </div>
    
    <v-list density="compact" nav class="px-3 mt-4">
      <v-list-item 
        v-for="(item, i) in navItems" 
        :key="i" 
        :href="item.to" 
        @click.prevent="scrollToSection(item.to); drawer = false"
        :title="item.title" 
        class="mb-2 rounded-lg py-2"
      ></v-list-item>
    </v-list>
    
    <template v-slot:append>
      <div class="pa-4 border-t border-opacity-10" v-if="!authStore.isAuthenticated">
        <v-btn block variant="outlined" color="primary" class="mb-4 pill-btn" to="/login">Log In</v-btn>
        <v-btn block color="secondary" variant="flat" class="pill-btn mb-4 text-background font-weight-bold" to="/register">Get Started</v-btn>
        <v-btn block variant="tonal" @click="toggleTheme" class="pill-btn">
          <v-icon left class="mr-2">{{ theme.global.current.value.dark ? 'mdi-white-balance-sunny' : 'mdi-moon-waning-crescent' }}</v-icon>
          {{ theme.global.current.value.dark ? 'Light Mode' : 'Dark Mode' }}
        </v-btn>
      </div>
      <div class="pa-4 border-t border-opacity-10" v-else>
        <v-btn block color="secondary" variant="flat" to="/dashboard" class="mb-4 pill-btn font-weight-bold">Dashboard</v-btn>
        <v-btn block variant="tonal" @click="toggleTheme" class="pill-btn mb-3">
          <v-icon left class="mr-2">{{ theme.global.current.value.dark ? 'mdi-white-balance-sunny' : 'mdi-moon-waning-crescent' }}</v-icon>
          {{ theme.global.current.value.dark ? 'Light Mode' : 'Dark Mode' }}
        </v-btn>
        <v-btn block color="error" variant="flat" @click="handleLogout" class="pill-btn font-weight-bold">
          <v-icon left class="mr-2">mdi-logout</v-icon>
          Log Out
        </v-btn>
      </div>
    </template>
  </v-navigation-drawer>
</template>

<style scoped>
.gap-6 { gap: 24px; }
.hover\:text-primary:hover {
  color: rgb(var(--v-theme-primary)) !important;
}
.pill-btn {
  border-radius: 50px !important;
  text-transform: none !important;
  letter-spacing: 0.5px !important;
}
.aurora-bg {
  background: 
    radial-gradient(ellipse 65% 55% at 50% -10%, rgba(34, 211, 238, 0.22) 0%, rgba(139, 92, 246, 0.28) 45%, rgba(11, 17, 32, 0) 100%),
    repeating-linear-gradient(90deg, rgba(255, 255, 255, 0.015) 0px, rgba(255, 255, 255, 0.015) 2px, transparent 2px, transparent 64px) !important;
}
</style>

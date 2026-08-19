<script setup>
import { ref, computed } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { useTheme, useDisplay } from 'vuetify'

const router = useRouter()
const route = useRoute()
const authStore = useAuthStore()
const theme = useTheme()
const { mobile } = useDisplay()
const drawer = ref(false)

const toggleTheme = () => {
  theme.global.name.value = theme.global.current.value.dark ? 'light' : 'dark'
}

const handleLogout = async () => {
  await authStore.logout()
  router.push('/login')
}

// Navigation links for public pages
const publicNavItems = [
  { title: 'Product', to: '/' },
  { title: 'Features', to: '/features' },
  { title: 'How It Works', to: '/how-it-works' },
  { title: 'Pricing', to: '/pricing' },
  { title: 'Testimonials', to: '/testimonials' }
]

// Navigation links for dashboard
const dashboardNavItems = [
  { title: 'Dashboard', icon: 'mdi-view-dashboard', to: '/dashboard' },
  { title: 'Upload Centre', icon: 'mdi-cloud-upload', to: '/dashboard/upload-centre' },
  { title: 'Performance', icon: 'mdi-speedometer', to: '/dashboard/student-performance' },
  { title: 'Exam Archives', icon: 'mdi-archive', to: '/dashboard/exam-archives' },
  { title: 'Leaderboard', icon: 'mdi-clipboard', to: '/dashboard/leaderboard' },
  { title: 'Settings', icon: 'mdi-cog', to: '/dashboard/settings' },
]

const teacherDashboardNavItems = [
  { title: 'Dashboard', icon: 'mdi-view-dashboard', to: '/dashboard' },
  { title: 'Upload Centre', icon: 'mdi-cloud-upload', to: '/dashboard/upload-centre' },
  { title: 'Performance', icon: 'mdi-speedometer', to: '/dashboard/student-performance' },
  { title: 'Tutor Profile', icon: 'mdi-account-school', to: '/dashboard/student-profile-tutor' },
  { title: 'Exam Archives', icon: 'mdi-archive', to: '/dashboard/exam-archives' },
  { title: 'Settings', icon: 'mdi-cog', to: '/dashboard/settings' },
]

const isDashboard = computed(() => {
  return route.path.startsWith('/dashboard') || route.path === '/admin-dashboard'
})

const currentMenuTitle = computed(() => {
  if (!isDashboard.value) return 'Dashboard'
  const navItems = authStore.activeRole === 'teacher' ? teacherDashboardNavItems : dashboardNavItems
  const currentItem = navItems.find(item => item.to === route.path)
  return currentItem ? currentItem.title : 'Dashboard'
})
</script>

<template>
  <v-app class="app-container">
    
    <!-- Public App Bar (Modern Floating Pill) -->
    <v-app-bar v-if="!isDashboard" color="transparent" elevation="0" height="100">
      <v-card class="mx-auto rounded-pill d-flex align-center px-4 px-md-8 w-100 border-opacity-10" max-width="1100" height="70"  elevation="8">
        <v-app-bar-nav-icon @click="drawer = !drawer" class="hidden-md-and-up"></v-app-bar-nav-icon>
        
        <!-- Logo Area -->
        <div class="d-flex align-center cursor-pointer" @click="router.push('/')">
          <v-avatar color="secondary" size="40" class="mr-3 " rounded="lg">
            <v-icon color="background">mdi-shield-check</v-icon>
          </v-avatar>
          <div class="text-h6 font-weight-bold d-flex align-center">
            GeliScope 
          </div>
        </div>

        <v-spacer></v-spacer>

        <!-- Centered Links (Public only) -->
        <div class="hidden-sm-and-down d-flex gap-6">
          <router-link v-for="item in publicNavItems" :key="item.title" :to="item.to" class="text-decoration-none text-muted font-weight-medium px-4 py-2 transition-fast-in-fast-out hover:text-primary" style="transition: color 0.3s;" active-class="text-primary font-weight-bold">
            {{ item.title }}
          </router-link>
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

    <!-- Dashboard Top Bar (Mobile Only) -->
    <v-app-bar v-if="isDashboard" class="d-lg-none bg-surface border-b border-opacity-10" elevation="0" height="70">
      <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>
      <div class="text-h6 font-weight-bold ml-2">{{ currentMenuTitle }}</div>
      <v-spacer></v-spacer>
    </v-app-bar>

    <!-- Navigation Drawer (Left Sidebar for Dashboard / Mobile Menu for Public) -->
    <v-navigation-drawer 
      v-model="drawer" 
      :permanent="isDashboard && !mobile" 
      :temporary="!isDashboard || mobile" 
      class="bg-cardBg border-r border-opacity-10 "
      width="280"
      style="height: 100vh; position: fixed; top: 0; left: 0;"
    >
      <div class="pa-4 d-flex align-center border-b border-opacity-10 cursor-pointer" @click="router.push('/')">
        <v-avatar color="secondary" size="36" class="mr-3 " rounded="lg">
          <v-icon color="background" size="small">mdi-shield-check</v-icon>
        </v-avatar>
        <span class="font-weight-black text-h5 tracking-tight">GeliScope</span>
      </div>
      
      <div class="px-3 pt-6 pb-2" v-if="isDashboard">
        <div class="text-caption text-uppercase font-weight-bold text-muted mb-2 px-2 tracking-widest">Main Menu</div>
      </div>
      
      <v-list density="compact" nav class="px-3">
        <template v-if="isDashboard && authStore.isAuthenticated">
          <v-list-item v-for="(item, i) in (authStore.activeRole === 'teacher' ? teacherDashboardNavItems : dashboardNavItems)" :key="i" :to="item.to" :prepend-icon="item.icon" :title="item.title" class="mb-2 rounded-lg py-2 transition-all" active-class="bg-primary text-white font-weight-bold "></v-list-item>
        </template>
        <template v-else>
          <v-list-item v-for="(item, i) in publicNavItems" :key="i" :to="item.to" :title="item.title" class="mb-2 rounded-lg py-2"></v-list-item>
        </template>
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
          <div class="d-flex align-center mb-6 px-2 bg-background rounded-xl pa-3 border border-opacity-5">
            <v-avatar color="primary" size="40" class="mr-3 text-white font-weight-bold ">
              <span class="text-h4 font-weight-bold" v-if="!authStore.currentUser.profile_photo_url">
              {{ authStore.currentUser.first_name ? authStore.currentUser.first_name[0] : 'U' }}
              </span>
              <v-img v-else :src="authStore.currentUser.profile_photo_url"></v-img>
            </v-avatar>
            <div class="overflow-hidden">
               <div class="text-body-2 font-weight-bold text-truncate">{{ authStore.currentUser?.first_name }} {{ authStore.currentUser?.last_name }}</div>
               <div class="text-caption text-primary text-uppercase font-weight-bold text-truncate">{{ authStore.currentUser?.role }}</div>
            </div>
          </div>
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

    <!-- Main Content Area -->
    <v-main class="bg-background position-relative w-100">
      <div class="glow-blur-purple" style="top: -100px; left: -100px;"></div>
      <div class="glow-blur-green" style="top: 40%; right: -150px;"></div>
      <router-view v-slot="{ Component }">
        <transition name="fade" mode="out-in">
          <component :is="Component" />
        </transition>
      </router-view>
    </v-main>

    <!-- Footer -->
    <v-footer v-if="!isDashboard" border="top" class="bg-background d-flex flex-column align-center text-center py-8 mt-16 border-opacity-10">
      <div class="d-flex align-center mb-4">
        <v-avatar color="secondary" size="24" class="mr-2" rounded="sm">
          <v-icon color="background" size="x-small">mdi-shield-check</v-icon>
        </v-avatar>
        <span class="font-weight-bold">GeliScope</span>
      </div>
      <div class="text-caption text-muted max-w-xl">
        AI Vision Assessment Engine built for inclusive and quality education.
      </div>
      <div class="text-caption text-muted mt-2">
        &copy; {{ new Date().getFullYear() }} GeliScope &mdash; All Rights Reserved
      </div>
    </v-footer>
  </v-app>
</template>

<style scoped>
.app-container {
  font-family: 'Outfit', sans-serif;
}
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease, transform 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
  transform: translateY(10px);
}
.hover\:text-primary:hover {
  color: rgb(var(--v-theme-primary)) !important;
}
.tracking-tight {
  letter-spacing: -0.05em;
}
.tracking-widest {
  letter-spacing: 0.1em;
}
</style>

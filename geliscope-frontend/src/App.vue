<script setup>
import { ref, computed } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { useTheme } from 'vuetify'

const router = useRouter()
const route = useRoute()
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
  { title: 'Leaderboard', icon: 'mdi-clipboard', to: '/dashboard/leaderboard' },
  { title: 'Settings', icon: 'mdi-cog', to: '/dashboard/settings' },
]

const isDashboard = computed(() => {
  return route.path.startsWith('/dashboard') || route.path === '/admin-dashboard'
})
</script>

<template>
  <v-app class="app-container ">
    <!-- Top App Bar -->
    <v-app-bar :elevation="isDashboard ? 1 : 0" :class="isDashboard ? 'bg-surface' : 'navbar-glass bg-transparent'" height="80">
      <v-container class="d-flex align-center px-0 w-100 fill-height" :max-width="isDashboard ? 'fluid' : '1200'">
        <v-app-bar-nav-icon @click="drawer = !drawer" class="hidden-md-and-up"></v-app-bar-nav-icon>
        
        <!-- Logo Area -->
        <div class="d-flex align-center cursor-pointer" @click="router.push('/')">
          <v-avatar color="secondary" size="40" class="mr-3 shadow-glow" rounded="lg">
            <v-icon color="background">mdi-shield-check</v-icon>
          </v-avatar>
          <div class="text-h6 font-weight-bold d-flex align-center">
            GeliScope 
          </div>
        </div>

        <v-spacer></v-spacer>

        <!-- Centered Links (Public only) -->
        <div v-if="!isDashboard" class="hidden-sm-and-down d-flex gap-6">
          <router-link v-for="item in publicNavItems" :key="item.title" :to="item.to" class="text-decoration-none text-muted font-weight-medium px-4 py-2 transition-fast-in-fast-out hover:text-white" style="transition: color 0.3s;" active-class="text-white">
            {{ item.title }}
          </router-link>
        </div>

        <v-spacer v-if="!isDashboard"></v-spacer>

        <!-- Right Side Auth / Dashboard Actions -->
        <template v-if="authStore.isAuthenticated">
          <div class="hidden-sm-and-down d-flex align-center gap-2">
            <template v-if="isDashboard">
              <v-btn v-for="item in dashboardNavItems" :key="item.title" variant="text" :to="item.to" :prepend-icon="item.icon" class="text-capitalize">{{ item.title }}</v-btn>
            </template>
            <template v-else>
              <v-btn color="secondary" variant="flat" to="/dashboard" class="pill-btn px-6 mx-2">Go to Dashboard</v-btn>
            </template>
            <v-btn icon @click="toggleTheme" variant="text" class="ml-2">
              <v-icon>{{ theme.global.current.value.dark ? 'mdi-white-balance-sunny' : 'mdi-moon-waning-crescent' }}</v-icon>
            </v-btn>
            <v-btn color="error" variant="flat" @click="handleLogout" class="pill-btn ml-2 text-white">Log Out</v-btn>
          </div>
        </template>
        <template v-else>
          <div class="hidden-sm-and-down d-flex align-center">
            <v-btn icon @click="toggleTheme" variant="text" class="mx-2">
              <v-icon>{{ theme.global.current.value.dark ? 'mdi-white-balance-sunny' : 'mdi-moon-waning-crescent' }}</v-icon>
            </v-btn>
            <v-btn variant="outlined" color="muted" class="pill-btn px-6 text-white border-opacity-50 mx-2" to="/login">Log In</v-btn>
            <v-btn color="secondary" variant="flat" class="pill-btn px-6 text-background font-weight-bold" to="/register">Get started for free</v-btn>
          </div>
        </template>
      </v-container>
    </v-app-bar>

    <!-- Navigation Drawer for Mobile -->
    <v-navigation-drawer v-model="drawer" temporary class="bg-cardBg">
      <div class="pa-4 d-flex align-center border-b border-opacity-10">
        <v-avatar color="secondary" size="32" class="mr-3" rounded="sm">
          <v-icon color="background" size="small">mdi-shield-check</v-icon>
        </v-avatar>
        <span class="font-weight-bold ">GeliScope</span>
      </div>
      <v-list density="compact" nav>
        <template v-if="isDashboard && authStore.isAuthenticated">
          <v-list-item v-for="(item, i) in dashboardNavItems" :key="i" :to="item.to" :prepend-icon="item.icon" :title="item.title"></v-list-item>
        </template>
        <template v-else>
          <v-list-item v-for="(item, i) in publicNavItems" :key="i" :to="item.to" :title="item.title"></v-list-item>
        </template>
      </v-list>
      <div class="pa-4" v-if="!authStore.isAuthenticated">
        <v-btn block variant="outlined" color="white" class="mb-4 pill-btn" to="/login">Log In</v-btn>
        <v-btn block color="secondary" variant="flat" class="pill-btn text-background mb-4" to="/register">Get started</v-btn>
        <v-btn block variant="tonal" @click="toggleTheme" class="pill-btn">
          Toggle {{ theme.global.current.value.dark ? 'Light' : 'Dark' }} Mode
        </v-btn>
      </div>
      <div class="pa-4" v-else>
        <v-btn block variant="tonal" @click="toggleTheme" class="pill-btn">
          Toggle {{ theme.global.current.value.dark ? 'Light' : 'Dark' }} Mode
        </v-btn>
        <v-btn block color="error" variant="flat" @click="handleLogout" class="pill-btn text-white">Log Out</v-btn>
      </div>
    </v-navigation-drawer>

    <!-- Main Content Area -->
    <v-main class="bg-background position-relative ">
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
.shadow-glow {
  box-shadow: 0 0 16px rgba(81, 250, 170, 0.4);
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
.hover\:text-white:hover {
  color: #FFFFFF !important;
}
</style>

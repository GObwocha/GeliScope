<script setup>
import { ref, computed } from 'vue'
import { useTheme } from 'vuetify'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const theme = useTheme()
const router = useRouter()
const authStore = useAuthStore()
const drawer = ref(false)

const handleLogout = async () => {
  await authStore.logout()
  router.push('/login')
}

const toggleTheme = () => {
  theme.global.name.value = theme.global.current.value.dark ? 'light' : 'dark'
}

const navItems = [
  { title: 'Home', icon: 'mdi-home-outline', to: '/' },
  { title: 'Features & AI', icon: 'mdi-line-scan', to: '/features' },
  { title: 'How It Works', icon: 'mdi-help-circle-outline', to: '/how-it-works' },
  { title: 'Dashboard', icon: 'mdi-view-dashboard-outline', to: '/dashboard' },
  { title: 'Upload Centre (Vision OCR)', icon: 'mdi-camera-outline', to: '/dashboard/upload-centre' },
  { title: 'Student Analytics', icon: 'mdi-chart-timeline-variant', to: '/dashboard/student-performance' },
  { title: 'Parent Portal', icon: 'mdi-account-group-outline', to: '/dashboard/student-profile-parent' },
]
</script>

<template>
  <v-app class="app-container">
    <!-- Top App Bar -->
    <v-app-bar elevation="2" border="bottom" color="surface">
      <v-app-bar-nav-icon @click="drawer = !drawer" class="hidden-md-and-up"></v-app-bar-nav-icon>
      
      <div class="d-flex align-center cursor-pointer ml-3" @click="router.push('/')">
        <v-avatar color="primary" size="38" class="mr-3 font-weight-bold shadow-glow">
          <span class="text-white">GS</span>
        </v-avatar>
        <div>
          <div class="text-h6 font-weight-bold d-flex align-center">
            GeliScope 
          </div>
          <div class="text-caption text-medium-emphasis">AI Vision Assessment Engine</div>
        </div>
      </div>

      <v-spacer></v-spacer>

      <div >
        
        <!-- <v-btn variant="text" to="/profile" color="accent" prepend-icon="mdi-account">Profile</v-btn> --> 
        <!-- Will review this after I set up the logins and sessions -->
        
        
      </div>

      

      <template v-if="authStore.isAuthenticated" class="hidden-sm-and-down d-flex align-center gap-2 mr-3">
        <v-btn variant="text" to="/dashboard" prepend-icon="mdi-view-dashboard">Dashboard</v-btn>
        <v-btn variant="text" to="/dashboard/upload-centre" prepend-icon="mdi-cloud-upload">Uploading Base</v-btn>
        <v-btn variant="text" to="/dashboard/student-performance" prepend-icon="mdi-speedometer">Performance</v-btn>
        <v-btn variant="text" to="/dashboard/leaderboard" prepend-icon="mdi-clipboard">Leaderboard</v-btn>
        <v-btn variant="text" to="/dashboard/settings" prepend-icon="mdi-cog">Settings</v-btn>
        <v-btn icon @click="toggleTheme" variant="text" color="primary">
        <v-icon>{{ theme.global.current.value.dark ? 'mdi-white-balance-sunny' : 'mdi-moon-waning-crescent' }}</v-icon>
      </v-btn>
        <v-btn color="error" variant="flat" @click="handleLogout" prepend-icon="mdi-logout" class="ml-2">
          Log Out
        </v-btn>
      </template>
      <template v-else >
        <v-btn variant="text" to="/" prepend-icon="mdi-home">Home</v-btn>
        <v-btn variant="text" to="/features" prepend-icon="mdi-line-scan">AI Vision</v-btn>
        <v-btn icon @click="toggleTheme" variant="text" color="primary">
        <v-icon>{{ theme.global.current.value.dark ? 'mdi-white-balance-sunny' : 'mdi-moon-waning-crescent' }}</v-icon>
      </v-btn>
        <v-btn color="primary" variant="flat" to="/login" prepend-icon="mdi-login" class="ml-2">Log In</v-btn>
      </template>
    </v-app-bar>

    <!-- Navigation Drawer for Mobile / Sidebar -->
    <v-navigation-drawer v-model="drawer" temporary>
      <v-list-item
        prepend-avatar="https://api.dicebear.com/7.x/bottts/svg?seed=GeliScope"
        title="GeliScope Navigation"
        subtitle="Ensure Quality Education"
      ></v-list-item>
      <v-divider></v-divider>
      <v-list density="compact" nav>
        <v-list-item v-for="(item, i) in navItems" :key="i" :to="item.to" :prepend-icon="item.icon" :title="item.title" color="primary"></v-list-item>
      </v-list>
    </v-navigation-drawer>

    <!-- Main Content Area -->
    <v-main>
      <router-view v-slot="{ Component }">
        <transition name="fade" mode="out-in">
          <component :is="Component" />
        </transition>
      </router-view>
    </v-main>

    <!-- Footer -->
    <v-footer border="top" class="bg-surface d-flex flex-column align-center text-center">
      <div class="d-flex align-center">
        <v-icon color="primary" class="mr-2">mdi-school-outline</v-icon>
        <span class="font-weight-bold">GeliScope Quality & Inclusive EdTech</span>
      </div>
      <div class="text-caption text-medium-emphasis max-w-xl">
        Built for Kenyan schools & global lifelong learning. Powered by GObwocha
      </div>
      <div class="text-caption text-medium-emphasis mt-2">
        &copy; {{ new Date().getFullYear() }} GeliScope &mdash; All Rights Reserved
      </div>
    </v-footer>
  </v-app>
</template>

<style>
.app-container {
  font-family: 'Outfit', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}
.shadow-glow {
  box-shadow: 0 0 16px rgba(99, 102, 241, 0.4);
}
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>

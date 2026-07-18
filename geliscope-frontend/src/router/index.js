import { createRouter, createWebHistory } from 'vue-router'
import Features from '@/components/Features.vue'
import HowItWorks from '@/components/HowItWorks.vue'
import LandingPage from '@/components/LandingPage.vue'
import Login from '@/components/Login.vue'
import Pricing from '@/components/Pricing.vue'
import Register from '@/components/Register.vue'
import Settings from '@/components/Settings.vue'
import StudentPerformance from '@/components/StudentPerformance.vue'
import StudentProfileP from '@/components/StudentProfileP.vue'
import StudentProfileS from '@/components/StudentProfileS.vue'
import StudentProfileT from '@/components/StudentProfileS.vue'
import Testimonials from '@/components/Testimonials.vue'
import UploadCentre from '@/components/UploadCentre.vue'
import Leaderboard from '@/components/Leaderboard.vue'
import AdminDashboard from '@/components/AdminDashboard.vue'
import UserDashboard from '@/components/UserDashboard.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      component: LandingPage
    },
    {
      path: '/features',
      component: Features
    },
    {
      path: '/how-it-works',
      component: HowItWorks
    },
    {
      path: '/admin-dashboard',
      component: AdminDashboard
    },
    {
      path: '/user-dashboard',
      component: UserDashboard
    },
    {
      path: '/dashboard/leaderboard',
      component: Leaderboard
    },
    {
      path: '/login',
      component: Login
    },
    {
      path: '/pricing',
      component: Pricing
    },
    {
      path: '/register',
      component: Register
    },
    {
      path: '/dashboard/settings',
      component: Settings
    },
    {
      path: '/dashboard/student-performance',
      component: StudentPerformance
    },
    {
      path: '/dashboard/student-profile-parent',
      component: StudentProfileP
    },
    {
      path: '/dashboard/student-profile-student',
      component: StudentProfileS
    },
    {
      path: '/dashboard/student-profile-tutor',
      component: StudentProfileT
    },
    {
      path: '/testimonials',
      component: Testimonials
    },
    {
      path: '/dashboard/upload-centre',
      component: UploadCentre
    }
  ],
})

export default router
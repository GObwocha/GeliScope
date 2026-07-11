import { createRouter, createWebHistory } from 'vue-router'
import Dashboard from '@/components/Dashboard.vue'
import Features from '@/components/Features.vue'
import HowItWorks from '@/components/HowItWorks.vue'
import LandingPage from '@/components/LandingPage.vue'
import Login from '@/components/Login.vue'
import Pricing from '@/components/Pricing.vue'
import RegisterAsParent from '@/components/RegisterAsParent.vue'
import RegisterAsStudent from '@/components/RegisterAsStudent.vue'
import RegisterAsTutor from '@/components/RegisterAsTutor.vue'
import Settings from '@/components/Settings.vue'
import StudentPerformance from '@/components/StudentPerformance.vue'
import StudentProfileP from '@/components/StudentProfileP.vue'
import StudentProfileS from '@/components/StudentProfileS.vue'
import StudentProfileT from '@/components/StudentProfileS.vue'
import Testimonials from '@/components/Testimonials.vue'
import UploadCentre from '@/components/UploadCentre.vue'

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
      path: '/dashboard',
      component: Dashboard
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
      path: '/register-as-parent',
      component: RegisterAsParent
    },
    {
      path: '/register-as-student',
      component: RegisterAsStudent
    },
    {
      path: '/register-as-tutor',
      component: RegisterAsTutor
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
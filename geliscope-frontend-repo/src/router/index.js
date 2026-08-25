import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import Features from '@/components/Features.vue'
import HowItWorks from '@/components/HowItWorks.vue'
import LandingPage from '@/components/LandingPage.vue'
import Login from '@/components/Login.vue'
import Pricing from '@/components/Pricing.vue'
import Register from '@/components/Register.vue'
import VerifyEmail from '@/components/VerifyEmail.vue'
import Settings from '@/components/Settings.vue'
import Performance from '@/components/Performance.vue'
import StudentProfileP from '@/components/StudentProfileP.vue'
import StudentProfileS from '@/components/StudentProfileS.vue'
import StudentProfileT from '@/components/StudentProfileS.vue'
import StudentProfileTutor from '@/components/StudentProfileTutor.vue'
import Testimonials from '@/components/Testimonials.vue'
import UploadCentre from '@/components/UploadCentre.vue'
import Leaderboard from '@/components/Leaderboard.vue'
import AdminDashboard from '@/components/AdminDashboard.vue'
import UserDashboard from '@/components/UserDashboard.vue'
import ExamArchives from '@/components/ExamArchives.vue'
import ClassDiagnosis from '@/components/ClassDiagnosis.vue'
import AuthCallBack from '@/components/AuthCallback.vue'


const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      component: LandingPage,
      meta: { requiresAuth: false }
    },
    {
    // This route handles the return from your Laravel backend
    path: '/google-callback',
    name: 'google-callback',
    component: AuthCallBack
    },
    {
      path: '/features',
      component: Features,
      meta: { requiresAuth: false }
    },
    {
      path: '/how-it-works',
      component: HowItWorks,
      meta: { requiresAuth: false }
    },
    {
      path: '/admin-dashboard',
      component: AdminDashboard,
      meta: { requiresAuth: true }
    },
    {
      path: '/dashboard',
      component: UserDashboard,
      meta: { requiresAuth: true }
    },
    {
      path: '/dashboard/leaderboard',
      component: Leaderboard,
      meta: { requiresAuth: true }
    },
    {
      path: '/login',
      component: Login,
      meta: { requiresAuth: false }
    },
    {
      path: '/verify-email',
      component: VerifyEmail,
      meta: { requiresAuth: false }
    },
    {
      path: '/pricing',
      component: Pricing,
      meta: { requiresAuth: false }
    },
    {
      path: '/register',
      component: Register,
      meta: { requiresAuth: false }
    },
    {
      path: '/dashboard/settings',
      component: Settings,
      meta: { requiresAuth: true }
    },
    {
      path: '/dashboard/performance',
      component: Performance,
      meta: { requiresAuth: true }
    },
    {
      path: '/dashboard/student-profile-parent',
      component: StudentProfileP,
      meta: { requiresAuth: true }
    },
    {
      path: '/dashboard/student-profile-student',
      component: StudentProfileS,
      meta: { requiresAuth: true }
    },
    {
      path: '/dashboard/student-profile-tutor',
      component: StudentProfileTutor,
      meta: { requiresAuth: true }
    },
    {
      path: '/dashboard/student-profile-teacher',
      component: StudentProfileT,
      meta: { requiresAuth: true }
    },
    {
      path: '/testimonials',
      component: Testimonials,
      meta: { requiresAuth: false }
    },
    {
      path: '/dashboard/upload-centre',
      component: UploadCentre,
      meta: { requiresAuth: true }
    },
    {
      path: '/dashboard/exam-archives',
      component: ExamArchives,
      meta: { requiresAuth: true }
    },
    {
      path: '/dashboard/class-diagnosis',
      name: 'ClassDiagnosis',
      component: ClassDiagnosis,
      meta: { requiresAuth: true, role: 'teacher' }
    }
  ],
})

router.beforeEach((to, from, next) => {
  const authStore = useAuthStore()
  
  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    next('/login')
  } else if ((to.path === '/login' || to.path === '/register') && authStore.isAuthenticated) {
    next('/dashboard')
  } else if (authStore.isAuthenticated && authStore.activeRole === 'parent' && (to.path === '/dashboard/leaderboard' || to.path === '/dashboard/exam-archives')) {
    next('/dashboard')
  } else {
    next()
  }
})

export default router
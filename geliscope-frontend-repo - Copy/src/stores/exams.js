import { defineStore } from 'pinia'
import axios from 'axios'
import { useAuthStore } from './auth'

export const useExamStore = defineStore('examStore', {
  state: () => ({
    trendExams: [],
    recentExams: [],
    isLoading: false,
    error: null,
  }),
  actions: {
    async fetchStudentExams(studentId) {
      if (!studentId) return

      this.isLoading = true
      this.error = null
      
      const authStore = useAuthStore()

      try {
        const response = await axios.get(`/api/v1/students/${studentId}/exam-results`, {
          headers: { Authorization: `Bearer ${authStore.token}` }
        })
        
        // Map backend MySQL models to the frontend UI format
        this.trendExams = response.data.map((result) => {
          const avg = result.max_score > 0 ? (result.total_score / result.max_score) * 100 : 0
          return {
            id: result.id,
            exam_code: 'RND-' + result.id,
            name: result.exam.name,
            period: result.exam.name,
            avg: parseFloat(avg.toFixed(1)),
            grade: 'N/A', // Round aggregate doesn't have a single grade
            stream_avg: result.stream_avg || 0,
            status: 'Verified',
            score: result.total_score,
            max_score: result.max_score,
            date: new Date().toISOString() // Or get round created_at
          }
        })

        // Sort recent exams by date descending (for rounds, just reverse id order)
        this.recentExams = [...this.trendExams].sort((a, b) => b.id - a.id)
        
      } catch (err) {
        console.error('Failed to fetch student exams:', err)
        this.error = 'Failed to load exam history'
      } finally {
        this.isLoading = false
      }
    },
    
    clearExams() {
      this.trendExams = []
      this.recentExams = []
      this.error = null
    }
  }
})

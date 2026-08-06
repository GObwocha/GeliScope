import { defineStore } from 'pinia'
import axios from 'axios'

export const useSchoolStore = defineStore('school', {
  state: () => ({
    schools: [],
    classes: [],
    loading: false,
    error: null,
  }),
  actions: {
    async fetchSchools() {
      this.loading = true
      this.error = null
      try {
        const response = await axios.get('/api/v1/schools')
        // Laravel's index route might return a paginator object or just an array
        this.schools = response.data.data || response.data
        console.log(response.data)
      } catch (err) {
        console.error('Failed to fetch schools:', err)
        this.error = 'Failed to load schools'
      } finally {
        this.loading = false
      }
    },
    async fetchClasses(schoolId) {
      if (!schoolId) {
        this.classes = []
        return
      }
      this.loading = true
      this.error = null
      try {
        const response = await axios.get(`/api/v1/schools/${schoolId}/classes`)
        this.classes = response.data.data || response.data
      } catch (err) {
        console.error('Failed to fetch classes:', err)
        this.error = 'Failed to load classes'
      } finally {
        this.loading = false
      }
    }
  }
})

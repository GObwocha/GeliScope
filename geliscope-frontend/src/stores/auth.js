import { defineStore } from 'pinia'
import axios from 'axios'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: JSON.parse(localStorage.getItem('geliscope_user')) || null,
    token: localStorage.getItem('geliscope_token') || null,
  }),
  
  getters: {
    isAuthenticated: (state) => !!state.token,
    currentUser: (state) => state.user,
    activeRole: (state) => state.user ? state.user.role : null,
  },
  
  actions: {
    async checkEmail(email) {
      try {
        const response = await axios.post('/api/v1/auth/check-email', { email })
        return response.data
      } catch (error) {
        throw error
      }
    },
    
    async login(email, password) {
      try {
        const response = await axios.post('/api/v1/auth/login', { email, password })
        
        this.user = response.data.user
        this.token = response.data.token
        
        localStorage.setItem('geliscope_user', JSON.stringify(this.user))
        localStorage.setItem('geliscope_token', this.token)
        
        return response.data
      } catch (error) {
        throw error
      }
    },
    
    async logout() {
      try {
        if (this.token) {
           await axios.post('/api/v1/auth/logout')
        }
      } catch (error) {
        console.error('Logout error:', error)
      } finally {
        this.user = null
        this.token = null
        localStorage.removeItem('geliscope_user')
        localStorage.removeItem('geliscope_token')
      }
    }
  }
})

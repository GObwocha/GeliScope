import { createApp } from 'vue'
import { createPinia } from 'pinia'
import vue3GoogleLogin from 'vue3-google-login'
import axios from 'axios'

// Configure Axios
axios.defaults.baseURL = '' // Handled by proxy
axios.interceptors.request.use(config => {
  const token = localStorage.getItem('geliscope_token')
  if (token) {
    config.headers.Authorization = `Bearer ${token}`
  }
  return config
})

import App from './App.vue'
import router from './router'
import vuetify from './plugins/vuetify'

const app = createApp(App)

app.use(createPinia())
app.use(router)
app.use(vuetify)
app.use(vue3GoogleLogin, {
  clientId: import.meta.env.VITE_GOOGLE_CLIENT_ID || '1234567890-sampleclientidforgeliscope.apps.googleusercontent.com'
})

app.mount('#app')

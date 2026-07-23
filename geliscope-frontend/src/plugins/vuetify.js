import '@mdi/font/css/materialdesignicons.css'
import 'vuetify/styles'
import { createVuetify } from 'vuetify'

export default createVuetify({
  theme: {
    defaultTheme: 'dark',
    themes: {
      dark: {
        dark: true,
        colors: {
          background: '#0C0E1D',
          surface: '#0C0E1D',
          primary: '#AB7DE3', // Purple
          secondary: '#51FAAA', // Bright Green
          accent: '#FF81FF', // Light Purple
          error: '#EF4444',
          info: '#3B82F6',
          success: '#10B981',
          warning: '#F59E0B',
          cardBg: '#211F36',
          muted: '#616083'
        }
      },
      light: {
        dark: false,
        colors: {
          background: '#F8FAFC',
          surface: '#FFFFFF',
          primary: '#AB7DE3',
          secondary: '#51FAAA',
          accent: '#FF81FF',
          error: '#DC2626',
          info: '#2563EB',
          success: '#16A34A',
          warning: '#D97706',
          cardBg: '#FFFFFF',
          muted: '#616083'
        }
      }
    }
  },
  defaults: {
    VCard: {
      elevation: 4,
      rounded: 'xl'
    },
    VBtn: {
      rounded: 'lg',
      fontWeight: 'bold'
    },
    VTextField: {
      variant: 'outlined',
      density: 'comfortable',
      rounded: 'lg'
    },
    VSelect: {
      variant: 'outlined',
      density: 'comfortable',
      rounded: 'lg'
    }
  }
})

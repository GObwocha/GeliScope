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
          background: '#0B1120',
          surface: '#111827',
          primary: '#8B5CF6', // Purple
          secondary: '#22D3EE', // Bright Green
          accent: '#C4B5FD', // Light Purple
          error: '#EF4444',
          info: '#3B82F6',
          success: '#10B981',
          warning: '#F59E0B',
          cardBg: '#1F2937',
          muted: '#94A3B8'
        }
      },
      light: {
        dark: false,
        colors: {
          background: '#F8FAFC',
          surface: '#FFFFFF',
          primary: '#7C3AED',
          secondary: '#06B6D4',
          accent: '#DDD6FE',
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

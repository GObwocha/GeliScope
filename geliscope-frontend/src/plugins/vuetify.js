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
          background: '#0B0F19',
          surface: '#131B2E',
          primary: '#6366F1', // Indigo 500
          secondary: '#A855F7', // Purple 500
          accent: '#10B981', // Emerald 500
          error: '#EF4444',
          info: '#3B82F6',
          success: '#10B981',
          warning: '#F59E0B',
          cardBg: '#1A233A'
        }
      },
      light: {
        dark: false,
        colors: {
          background: '#F8FAFC',
          surface: '#FFFFFF',
          primary: '#4F46E5',
          secondary: '#9333EA',
          accent: '#059669',
          error: '#DC2626',
          info: '#2563EB',
          success: '#16A34A',
          warning: '#D97706',
          cardBg: '#FFFFFF'
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

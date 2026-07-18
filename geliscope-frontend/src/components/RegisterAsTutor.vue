<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const loading = ref(false)

const form = ref({
  first_name: '',
  last_name: '',
  email: '',
  password: '',
  employee_number: '',
  school_id: null,
  specialization: ''
})

const schools = ref([
  { id: 1, name: 'Alliance High School' },
  { id: 2, name: 'Maseno School' },
  { id: 3, name: 'Kenya High School' }
])

const submit = () => {
  loading.value = true
  console.log('Registering teacher:', form.value)
  // Simulate API call and redirect
  setTimeout(() => {
    loading.value = false
    router.push('/admin-dashboard')
  }, 1000)
}
</script>

<template>
  <v-container fluid class="pa-0 min-vh-100 font-family-sans bg-background">
    <v-row no-gutters class="min-vh-100">
      
      <!-- Left Side Branding (Hidden on mobile) -->
      <v-col cols="12" md="5" class="bg-surface d-none d-md-flex flex-column justify-center align-center px-12 border-e">
        <div class="text-center max-w-md">
          <v-icon size="80" color="primary" class="mb-6">mdi-human-chalkboard</v-icon>
          <h1 class="text-h3 font-weight-black mb-4">GeliScope</h1>
          <p class="text-h6 text-medium-emphasis font-weight-regular lh-tight">
            Empower your teaching with data-driven insights and streamlined classroom management.
          </p>
        </div>
      </v-col>

      <!-- Right Side Form -->
      <v-col cols="12" md="7" class="d-flex flex-column justify-center px-6 px-md-16 py-12">
        <div class="max-w-lg mx-auto w-100">
          <div class="mb-8">
            <h2 class="text-h4 font-weight-bold mb-2">Create Teacher Account</h2>
            <p class="text-body-1 text-medium-emphasis">Enter your professional details below.</p>
          </div>

          <!-- Google Auth Button -->
          <v-btn 
            block 
            variant="outlined" 
            size="large" 
            class="mb-6 rounded-lg font-weight-bold text-none border-medium"
            prepend-icon="mdi-google"
          >
            Sign up with Google
          </v-btn>

          <div class="d-flex align-center mb-6">
            <v-divider></v-divider>
            <span class="px-4 text-caption text-medium-emphasis font-weight-bold text-uppercase">Or sign up with email</span>
            <v-divider></v-divider>
          </div>

          <!-- Registration Form -->
          <v-form @submit.prevent="submit">
            <v-row dense>
              <v-col cols="12" sm="6">
                <v-text-field
                  v-model="form.first_name"
                  label="First Name"
                  variant="outlined"
                  bg-color="surface"
                  density="comfortable"
                  required
                ></v-text-field>
              </v-col>
              <v-col cols="12" sm="6">
                <v-text-field
                  v-model="form.last_name"
                  label="Last Name"
                  variant="outlined"
                  bg-color="surface"
                  density="comfortable"
                  required
                ></v-text-field>
              </v-col>
            </v-row>

            <v-text-field
              v-model="form.email"
              label="Email Address"
              type="email"
              variant="outlined"
              bg-color="surface"
              density="comfortable"
              required
            ></v-text-field>

            <v-text-field
              v-model="form.password"
              label="Password"
              type="password"
              variant="outlined"
              bg-color="surface"
              density="comfortable"
              required
            ></v-text-field>
            
            <h3 class="text-subtitle-1 font-weight-bold mt-4 mb-2">Professional Details</h3>

            <v-row dense>
              <v-col cols="12" sm="6">
                <v-text-field
                  v-model="form.employee_number"
                  label="Employee Number (TSC)"
                  variant="outlined"
                  bg-color="surface"
                  density="comfortable"
                  required
                ></v-text-field>
              </v-col>
              <v-col cols="12" sm="6">
                <v-text-field
                  v-model="form.specialization"
                  label="Specialization (e.g., Mathematics)"
                  variant="outlined"
                  bg-color="surface"
                  density="comfortable"
                ></v-text-field>
              </v-col>
            </v-row>

            <v-select
              v-model="form.school_id"
              :items="schools"
              item-title="name"
              item-value="id"
              label="Assigned School"
              variant="outlined"
              bg-color="surface"
              density="comfortable"
              required
            ></v-select>

            <v-btn
              type="submit"
              color="primary"
              block
              size="large"
              class="mt-6 rounded-lg font-weight-bold text-none"
              :loading="loading"
            >
              Create Account
            </v-btn>
          </v-form>

          <div class="text-center mt-6 text-body-2 text-medium-emphasis">
            Already have an account? 
            <router-link to="/login" class="text-primary font-weight-bold text-decoration-none">Sign in</router-link>
          </div>
        </div>
      </v-col>

    </v-row>
  </v-container>
</template>

<style scoped>
.min-vh-100 {
  min-height: 100vh;
}
.max-w-md {
  max-width: 400px;
}
.max-w-lg {
  max-width: 500px;
}
.lh-tight {
  line-height: 1.3 !important;
}
.border-e {
  border-right: 1px solid rgba(var(--v-border-color), 0.1);
}
.border-medium {
  border: 1px solid rgba(var(--v-border-color), 0.3) !important;
}
.font-family-sans {
  font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}
</style>

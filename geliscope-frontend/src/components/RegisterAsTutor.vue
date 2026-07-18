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
  <div class="w-100">
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
  </div>
</template>

<style scoped>
.border-medium {
  border: 1px solid rgba(var(--v-border-color), 0.3) !important;
}
</style>

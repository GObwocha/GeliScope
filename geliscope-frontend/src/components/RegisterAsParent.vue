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
  phone_number: '',
  occupation: ''
})

const submit = () => {
  loading.value = true
  console.log('Registering parent:', form.value)
  // Simulate API call and redirect
  setTimeout(() => {
    loading.value = false
    router.push('/user-dashboard')
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
      
      <h3 class="text-subtitle-1 font-weight-bold mt-4 mb-2">Contact Details</h3>

      <v-row dense>
        <v-col cols="12" sm="6">
          <v-text-field
            v-model="form.phone_number"
            label="Phone Number"
            variant="outlined"
            bg-color="surface"
            density="comfortable"
            required
          ></v-text-field>
        </v-col>
        <v-col cols="12" sm="6">
          <v-text-field
            v-model="form.occupation"
            label="Occupation"
            variant="outlined"
            bg-color="surface"
            density="comfortable"
          ></v-text-field>
        </v-col>
      </v-row>

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

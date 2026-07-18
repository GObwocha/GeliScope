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
  admission_number: '',
  school_id: null,
  current_class_id: null,
  date_of_birth: ''
})

const schools = ref([
  { id: 1, name: 'Alliance High School' },
  { id: 2, name: 'Maseno School' },
  { id: 3, name: 'Kenya High School' }
])

const classes = ref([
  { id: 1, name: 'Form 1' },
  { id: 2, name: 'Form 2' },
  { id: 3, name: 'Form 3' },
  { id: 4, name: 'Form 4' }
])

const submit = () => {
  loading.value = true
  console.log('Registering student:', form.value)
  // Simulate API call and redirect
  setTimeout(() => {
    loading.value = false
    router.push('/dashboard')
  }, 1000)
}
</script>

<template>
  <div class="w-100">
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
      
      <h3 class="text-subtitle-1 font-weight-bold mt-4 mb-2">Academic Details</h3>

      <v-row dense>
        <v-col cols="12" sm="6">
          <v-text-field
            v-model="form.admission_number"
            label="Admission Number"
            variant="outlined"
            bg-color="surface"
            density="comfortable"
            required
          ></v-text-field>
        </v-col>
        <v-col cols="12" sm="6">
          <v-text-field
            v-model="form.date_of_birth"
            label="Date of Birth"
            type="date"
            variant="outlined"
            bg-color="surface"
            density="comfortable"
            required
          ></v-text-field>
        </v-col>
      </v-row>

      <v-row dense>
        <v-col cols="12" sm="6">
          <v-select
            v-model="form.school_id"
            :items="schools"
            item-title="name"
            item-value="id"
            label="School"
            variant="outlined"
            bg-color="surface"
            density="comfortable"
            required
          ></v-select>
        </v-col>
        <v-col cols="12" sm="6">
          <v-select
            v-model="form.current_class_id"
            :items="classes"
            item-title="name"
            item-value="id"
            label="Current Class"
            variant="outlined"
            bg-color="surface"
            density="comfortable"
            required
          ></v-select>
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

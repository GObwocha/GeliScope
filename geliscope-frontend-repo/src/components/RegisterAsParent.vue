<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import { useAuthStore } from '@/stores/auth'

const router = useRouter()
const authStore = useAuthStore()
const loading = ref(false)
const errorMsg = ref('')
const formRef = ref(null)

const form = ref({
 first_name: '',
 last_name: '',
 email: '',
 password: '',
 phone_number: '',
 occupation: '',
 role: 'parent'
})

const rules = {
 required: value => !!value || 'Required.',
 min: v => v.length >= 8 || 'Min 8 characters',
 email: v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
}

const submit = async () => {
 const { valid } = await formRef.value.validate()
 if (!valid) return

 loading.value = true
 errorMsg.value = ''
 try {
 await axios.post('/api/v1/register-user', form.value)
 await authStore.login(form.value.email, form.value.password)
 router.push('/user-dashboard')
 } catch (error) {
 console.error(error)
 if (error.response?.data?.errors) {
 const errors = error.response.data.errors
 errorMsg.value = Object.values(errors).map(err => err[0]).join(' ')
 } else {
 errorMsg.value = error.response?.data?.message || 'Registration failed.'
 }
 } finally {
 loading.value = false
 }
}
</script>

<template>
 <div class="w-100">
 <v-alert v-if="errorMsg" type="error" variant="tonal" class="mb-4 text-caption" density="compact">{{ errorMsg }}</v-alert>
 <!-- Registration Form -->
 <v-form ref="formRef" @submit.prevent="submit">
 <v-row dense>
 <v-col cols="12" sm="6">
 <v-text-field
 v-model="form.first_name"
 label="First Name"
 variant="outlined"
 bg-color="surface"
 density="comfortable"
 :rules="[rules.required]"
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
 :rules="[rules.required]"
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
 :rules="[rules.required, rules.email]"
 required
 ></v-text-field>

 <v-text-field
 v-model="form.password"
 label="Password"
 type="password"
 variant="outlined"
 bg-color="surface"
 density="comfortable"
 :rules="[rules.required, rules.min]"
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

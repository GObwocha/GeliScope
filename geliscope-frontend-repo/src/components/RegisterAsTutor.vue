<script setup>
import { ref, onMounted, watch } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import { useAuthStore } from '@/stores/auth'
import { useSchoolStore } from '@/stores/schools'

const router = useRouter()
const authStore = useAuthStore()
const schoolStore = useSchoolStore()

const loading = ref(false)
const errorMsg = ref('')
const formRef = ref(null)

const subjects = ref([])
const classesList = ref([])

const form = ref({
 first_name: '',
 last_name: '',
 email: '',
 phone_number: '',
 password: '',
 employee_number: '',
 school_id: null,
 assigned_class_id: null,
 assigned_subject_id: null,
 specialization: '',
 role: 'teacher'
})

const rules = {
 required: value => !!value || 'Required.',
 min: v => v.length >= 8 || 'Min 8 characters',
 email: v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
}

onMounted(async () => {
 schoolStore.fetchSchools()
 try {
   const { data } = await axios.get('/api/v1/subjects')
   subjects.value = data.data || data
 } catch (err) {
   console.error(err)
 }
})

watch(() => form.value.school_id, async (newSchoolId) => {
  form.value.assigned_class_id = null
  if (newSchoolId) {
    try {
      const { data } = await axios.get(`/api/v1/schools/${newSchoolId}/classes`)
      classesList.value = data.data || data
    } catch (err) {
      console.error(err)
      classesList.value = []
    }
  } else {
    classesList.value = []
  }
})

const submit = async () => {
 const { valid } = await formRef.value.validate()
 if (!valid) return

 loading.value = true
 errorMsg.value = ''
 try {
 await axios.post('/api/v1/register-user', form.value)
 router.push('/login?registered=true')
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

 density="comfortable"
 :rules="[rules.required, rules.email]"
 required
 ></v-text-field>

 <v-text-field
 v-model="form.phone_number"
 label="Phone Number"
 variant="outlined"

 density="comfortable"
 ></v-text-field>

 <v-text-field
 v-model="form.password"
 label="Password"
 type="password"
 variant="outlined"

 density="comfortable"
 :rules="[rules.required, rules.min]"
 required
 ></v-text-field>
 
 <h3 class="text-subtitle-1 font-weight-bold mt-4 mb-2">Professional Details</h3>

 <v-row dense>
 <v-col cols="12" sm="6">
 <v-text-field
 v-model="form.employee_number"
 label="Employee Number (TSC)"
 variant="outlined"

 density="comfortable"
 :rules="[rules.required]"
 required
 ></v-text-field>
 </v-col>
 <v-col cols="12" sm="6">
 <v-text-field
 v-model="form.specialization"
 label="Specialization (e.g., Mathematics)"
 variant="outlined"

 density="comfortable"
 ></v-text-field>
 </v-col>
 </v-row>

 <v-select
 v-model="form.school_id"
 :items="schoolStore.schools"
 item-title="name"
 item-value="id"
 label="Assigned School"
 variant="outlined"

 density="comfortable"
 :rules="[rules.required]"
 :loading="schoolStore.loading"
 required
 ></v-select>

   <v-row dense>
     <v-col cols="12" sm="6">
       <v-select
         v-model="form.assigned_class_id"
         :items="classesList"
         item-title="name"
         item-value="id"
         label="Assigned Class (Optional)"
         variant="outlined"
         density="comfortable"
         :disabled="!form.school_id"
       ></v-select>
     </v-col>
     <v-col cols="12" sm="6">
       <v-select
         v-model="form.assigned_subject_id"
         :items="subjects"
         item-title="name"
         item-value="id"
         label="Teaching Subject (Optional)"
         variant="outlined"
         density="comfortable"
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

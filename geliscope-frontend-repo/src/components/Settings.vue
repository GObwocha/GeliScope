<script setup>
import { ref, computed, onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'
import axios from 'axios'
import { useExamStore } from '@/stores/exams'
import { useSchoolStore } from '@/stores/schools'

const authStore = useAuthStore()
const schoolStore = useSchoolStore()
const loading = ref(false)

// Simulating logged-in user context
const currentUser = computed(() => {
 return authStore.currentUser || {
 role: 'student', // 'student', 'teacher', or 'parent'
 first_name: 'Isabella',
 last_name: 'Lewis',
 email: 'isabella.lewis@example.com',
 password: '',
 profile_photo_url: null,
 
 // Student specific
 admission_number: '',
 school_id: 1,
 current_class_id: 2,
 date_of_birth: '',
 personal_motto: '',

 // Parent specific
 phone_number: '',
 occupation: '',

 // Teacher specific
 employee_number: '',
 specialization: ''
 }
})

const classes = ref([
 { id: 1, name: 'Form 1' },
 { id: 2, name: 'Form 2' },
 { id: 3, name: 'Form 3' },
 { id: 4, name: 'Form 4' }
])

const activeTab = ref(0)
const snackbar = ref(false)
const errorMessage = ref('')
const errorSnackbar = ref(false)

const mottoFontType = ref(localStorage.getItem('motto_font_type') || 'Inter')
const mottoFontSize = ref(localStorage.getItem('motto_font_size') || '16')
const mottoFontColor = ref(localStorage.getItem('motto_font_color') || '#000000')

const commonFonts = [
 'Inter', 'Roboto', 'Open Sans', 'Lato', 'Montserrat', 
 'Poppins', 'Oswald', 'Arial', 'Times New Roman', 
 'Courier New', 'Comic Sans MS'
]

const mottoError = computed(() => {
 if (!currentUser.value.personal_motto) return ''
 const words = currentUser.value.personal_motto.trim().split(/\s+/)
 return words.length > 7 ? 'Motto cannot exceed 7 words.' : ''
})

const teacherExams = ref([])
const selectedMarkingExam = ref(null)
const markingInstructionsText = ref('')

const fetchTeacherData = async () => {
  if (currentUser.value.role === 'teacher' && currentUser.value.id) {
    try {
      const response = await axios.get(`/api/v1/analysis/teacher/${currentUser.value.teacher.id}/classes`, {
        headers: { Authorization: `Bearer ${authStore.token}` }
      })
      const examsMap = new Map()
      response.data.forEach(cls => {
        if (cls.exams) {
          cls.exams.forEach(ex => {
            examsMap.set(ex.id, ex)
          })
        }
      })
      teacherExams.value = Array.from(examsMap.values())
    } catch (error) {
      console.error('Error fetching teacher data:', error)
    }
  }
}

const fetchMarkingConfig = async (examId) => {
  if (!examId) return
  markingInstructionsText.value = ''
  try {
    const response = await axios.get(`/api/v1/analysis/marking-config/${examId}`, {
      headers: { Authorization: `Bearer ${authStore.token}` }
    })
    markingInstructionsText.value = response.data.instructions?.raw_text || ''
  } catch (error) {
    console.error('Error fetching marking config:', error)
    markingInstructionsText.value = ''
  }
}

onMounted(() => {
  fetchTeacherData()
  schoolStore.fetchSchools()
})

 const saveChanges = async () => {
  if (mottoError.value) return
  loading.value = true
  
  if (currentUser.value.role === 'student') {
  localStorage.setItem('motto_font_type', mottoFontType.value)
  localStorage.setItem('motto_font_size', mottoFontSize.value)
  localStorage.setItem('motto_font_color', mottoFontColor.value)
  }

  if (currentUser.value.role === 'teacher' && selectedMarkingExam.value) {
    try {
      await axios.post('/api/v1/analysis/marking-config', {
          teacher_id: currentUser.value.id,
          exam_id: selectedMarkingExam.value,
          instructions: { raw_text: markingInstructionsText.value }
      }, {
          headers: { Authorization: `Bearer ${authStore.token}` }
      })
    } catch (error) {
      console.error('Error saving marking config:', error)
    }
  }

  loading.value = false
  snackbar.value = true
  console.log('Settings saved:', currentUser.value)
}

// Avatar Upload Logic
const fileInput = ref(null)
const uploadingAvatar = ref(false)

const triggerFileInput = () => {
  if (fileInput.value) {
    fileInput.value.click()
  }
}

const handleFileSelected = async (event) => {
  const file = event.target.files[0]
  if (!file) return

  if (file.size > 2 * 1024 * 1024) {
    errorMessage.value = 'Image size must be less than 2MB'
    errorSnackbar.value = true
    return
  }

  const formData = new FormData()
  formData.append('avatar', file)

  try {
    uploadingAvatar.value = true
    const response = await axios.post(`/api/v1/users/${currentUser.value.id}/avatar`, formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
        Authorization: `Bearer ${authStore.token}`
      }
    })

    const newUrl = response.data.profile_photo_url
    // Update local reactive user and the auth store
    currentUser.value.profile_photo_url = newUrl
    if (authStore.user) {
      authStore.user.profile_photo_url = newUrl
      localStorage.setItem('geliscope_user', JSON.stringify(authStore.user))
    }

    snackbar.value = true
    errorMessage.value = 'Avatar updated successfully!'
  } catch (err) {
    console.error('Avatar upload failed', err)
    errorMessage.value = 'Failed to upload avatar. Please try again.'
    errorSnackbar.value = true
  } finally {
    uploadingAvatar.value = false
    event.target.value = '' // reset input
  }
}

const removingAvatar = ref(false)

const removeAvatar = async () => {
  if (!confirm('Are you sure you want to remove your profile photo?')) return;
  
  try {
    removingAvatar.value = true
    await axios.delete(`/api/v1/users/${currentUser.value.id}/avatar`, {
      headers: { Authorization: `Bearer ${authStore.token}` }
    })

    currentUser.value.profile_photo_url = null
    if (authStore.user) {
      authStore.user.profile_photo_url = null
      localStorage.setItem('geliscope_user', JSON.stringify(authStore.user))
    }

    snackbar.value = true
    errorMessage.value = 'Avatar removed successfully!'
  } catch (err) {
    console.error('Avatar removal failed', err)
    errorMessage.value = 'Failed to remove avatar. Please try again.'
    errorSnackbar.value = true
  } finally {
    removingAvatar.value = false
  }
}
</script>

<template>
 <v-container  class="pa-6 bg-background min-vh-100 font-family-sans">
 <div class="max-w-4xl mx-auto">
 
 <!-- Header -->
 <div class="mb-8">
 <h1 class="text-h3 font-weight-black mb-2">Settings</h1>
 <p class="text-body-1 text-medium-emphasis">Manage your profile information and account preferences.</p>
 </div>

 <v-card class="rounded-xl border bg-surface overflow-hidden elevation-0">
 <div class="d-flex flex-column flex-md-row">
 
 <!-- Sidebar Navigation -->
 <div class="bg-surface-light border-e sidebar-nav">
 <v-tabs v-model="activeTab" direction="vertical" color="primary">
 <v-tab value="0" class="justify-start px-6 font-weight-bold text-none">
 <v-icon start class="mr-3">mdi-account-outline</v-icon> Personal Info
 </v-tab>
 <v-tab value="1" class="justify-start px-6 font-weight-bold text-none">
 <v-icon start class="mr-3">mdi-school-outline</v-icon> Academic/Role Details
 </v-tab>
 <v-tab value="2" class="justify-start px-6 font-weight-bold text-none">
 <v-icon start class="mr-3">mdi-shield-lock-outline</v-icon> Security
 </v-tab>
 <v-tab v-if="currentUser.role === 'teacher'" value="3" class="justify-start px-6 font-weight-bold text-none">
 <v-icon start class="mr-3">mdi-clipboard-check-outline</v-icon> Marking Configurations
 </v-tab>
 </v-tabs>
 </div>

 <!-- Main Content Area -->
 <div class="flex-grow-1 pa-8">
 <v-form @submit.prevent="saveChanges">
 
 <v-window v-model="activeTab">
 <!-- 1. Personal Info Tab -->
 <v-window-item value="0">
 <h3 class="text-h5 font-weight-bold mb-6">Personal Information</h3>
 
 <!-- Profile Photo -->
 <div class="d-flex align-center gap-6 mb-8">
 <v-avatar size="80" color="primary" variant="tonal">
 <v-progress-circular v-if="uploadingAvatar || removingAvatar" indeterminate color="primary"></v-progress-circular>
 <span class="text-h4 font-weight-bold" v-else-if="!currentUser.profile_photo_url">
 {{ currentUser.first_name ? currentUser.first_name[0] : 'U' }}
 </span>
 <v-img v-else :src="currentUser.profile_photo_url"></v-img>
 </v-avatar>
 <div>
 <input type="file" ref="fileInput" accept="image/jpeg, image/png, image/gif" class="d-none" @change="handleFileSelected" />
 <div class="d-flex gap-2 mb-2">
   <v-btn variant="outlined" size="small" class="rounded-lg text-none font-weight-bold" @click="triggerFileInput" :loading="uploadingAvatar">Change Avatar</v-btn>
   <v-btn v-if="currentUser.profile_photo_url" variant="text" color="error" size="small" class="rounded-lg text-none font-weight-bold" @click="removeAvatar" :loading="removingAvatar">Remove</v-btn>
 </div>
 <div class="text-caption text-medium-emphasis">JPG, GIF or PNG. Max size of 2MB</div>
 </div>
 </div>

 <v-row dense>
 <v-col cols="12" sm="6">
 <v-text-field
 v-model="currentUser.first_name"
 label="First Name"
 variant="outlined"
 bg-color="background"
 density="comfortable"
 ></v-text-field>
 </v-col>
 <v-col cols="12" sm="6">
 <v-text-field
 v-model="currentUser.last_name"
 label="Last Name"
 variant="outlined"
 bg-color="background"
 density="comfortable"
 ></v-text-field>
 </v-col>
 </v-row>

 <v-text-field
 v-model="currentUser.email"
 label="Email Address"
 type="email"
 variant="outlined"
 bg-color="background"
 density="comfortable"
 ></v-text-field>

 <template v-if="currentUser.role === 'student'">
 <v-divider class="my-6"></v-divider>
 <h3 class="text-h6 font-weight-bold mb-4">Personal Motto</h3>
 
 <v-text-field
 v-model="currentUser.personal_motto"
 label="Your Motto (Max 7 words)"
 variant="outlined"
 bg-color="background"
 density="comfortable"
 :error-messages="mottoError"
 class="mb-4"
 ></v-text-field>

 <v-row dense>
 <v-col cols="12" sm="4">
 <v-autocomplete
 v-model="mottoFontType"
 :items="commonFonts"
 label="Font Type"
 variant="outlined"
 bg-color="background"
 density="comfortable"
 ></v-autocomplete>
 </v-col>
 <v-col cols="12" sm="4">
 <v-text-field
 v-model="mottoFontSize"
 label="Font Size (px, max 24)"
 type="number"
 max="24"
 variant="outlined"
 bg-color="background"
 density="comfortable"
 ></v-text-field>
 </v-col>
 <v-col cols="12" sm="4">
 <v-text-field
 v-model="mottoFontColor"
 label="Font Color"
 type="color"
 variant="outlined"
 bg-color="background"
 density="comfortable"
 class="motto-color-picker"
 ></v-text-field>
 </v-col>
 </v-row>
 </template>

 </v-window-item>

 <!-- 2. Academic/Role Details Tab -->
 <v-window-item value="1">
 <h3 class="text-h5 font-weight-bold mb-6">Profile Details</h3>
 
 <div class="mb-4">
 <v-chip color="primary" variant="tonal" class="font-weight-bold text-uppercase mb-6" size="small">
 Role > {{ currentUser.role }}
 </v-chip>
 </div>

 <!-- Student Specific -->
 <template v-if="currentUser.role === 'student'">
 <v-row dense>
 <v-col cols="12" sm="6">
 <v-text-field
 v-model="currentUser.student.admission_number"
 label="Admission Number"
 variant="outlined"
 bg-color="background"
 density="comfortable"
 readonly
 hint="Contact admin to change"
 persistent-hint
 ></v-text-field>
 </v-col>
 <v-col cols="12" sm="6">
 <v-text-field
 v-model="currentUser.student.date_of_birth"
 label="Date of Birth"
 type="date"
 variant="outlined"
 bg-color="background"
 density="comfortable"
 ></v-text-field>
 </v-col>
 </v-row>
 <v-row dense class="mt-4">
 <v-col cols="12" sm="6">
 <v-select
 v-model="currentUser.student.school_id"
 :items="schoolStore.schools"
 item-title="name"
 item-value="id"
 label="School"
 variant="outlined"
 bg-color="background"
 density="comfortable"
 ></v-select>
 </v-col>
 <v-col cols="12" sm="6">
 <v-select
 v-model="currentUser.student.current_class_id"
 :items="classes"
 item-title="name"
 item-value="id"
 label="Current Class"
 variant="outlined"
 bg-color="background"
 density="comfortable"
 ></v-select>
 </v-col>
 </v-row>
 </template>

 <!-- Teacher Specific -->
 <template v-if="currentUser.role === 'teacher'">
 <v-row dense>
 <v-col cols="12" sm="6">
 <v-text-field
 v-model="currentUser.teacher.employee_number"
 label="Employee Number (TSC)"
 variant="outlined"
 bg-color="background"
 density="comfortable"
 readonly
 ></v-text-field>
 </v-col>
 <v-col cols="12" sm="6">
 <v-text-field
 v-model="currentUser.teacher.specialization"
 label="Specialization"
 variant="outlined"
 bg-color="background"
 density="comfortable"
 ></v-text-field>
 </v-col>
 </v-row>
 <v-select
 v-model="currentUser.teacher.school_id"
 :items="schoolStore.schools"
 item-title="name"
 item-value="id"
 label="Assigned School"
 variant="outlined"
 bg-color="background"
 density="comfortable"
 class="mt-4"
 ></v-select>
 </template>

 <!-- Parent Specific -->
 <template v-if="currentUser.role === 'parent'">
 <v-row dense>
 <v-col cols="12" sm="6">
 <v-text-field
 v-model="currentUser.phone_number"
 label="Phone Number"
 variant="outlined"
 bg-color="background"
 density="comfortable"
 ></v-text-field>
 </v-col>
 <v-col cols="12" sm="6">
 <v-text-field
 v-model="currentUser.occupation"
 label="Occupation"
 variant="outlined"
 bg-color="background"
 density="comfortable"
 ></v-text-field>
 </v-col>
 </v-row>
 </template>

 </v-window-item>

 <!-- 3. Security Tab -->
 <v-window-item value="2">
 <h3 class="text-h5 font-weight-bold mb-6">Security Settings</h3>
 <div class="mb-4 text-body-2 text-medium-emphasis">Change your account password here. Leave blank if you don't want to change it.</div>
 
 <v-text-field
 label="Current Password"
 type="password"
 variant="outlined"
 bg-color="background"
 density="comfortable"
 ></v-text-field>

 <v-text-field
 label="New Password"
 type="password"
 variant="outlined"
 bg-color="background"
 density="comfortable"
 ></v-text-field>

 <v-text-field
 label="Confirm New Password"
 type="password"
 variant="outlined"
 bg-color="background"
 density="comfortable"
 ></v-text-field>
 </v-window-item>

 <!-- 4. Marking Configurations Tab (Teacher Only) -->
 <v-window-item value="3" v-if="currentUser.role === 'teacher'">
 <h3 class="text-h5 font-weight-bold mb-6">Marking Configurations</h3>
 <div class="mb-4 text-body-2 text-medium-emphasis">Configure custom AI grading instructions. Specify keywords and specific things to look out for during automated grading.</div>
 
 <v-select
 v-model="selectedMarkingExam"
 :items="teacherExams"
 item-title="name"
 item-value="id"
 label="Select Exam"
 variant="outlined"
 bg-color="background"
 density="comfortable"
 @update:modelValue="fetchMarkingConfig"
 ></v-select>
 
 <div v-if="selectedMarkingExam">
 <v-textarea
 v-model="markingInstructionsText"
 label="Marking Instructions (Keywords, rules, expectations)"
 variant="outlined"
 bg-color="background"
 rows="6"
 hint="Example: Look out for formula derivation. Award partial marks for substitution."
 persistent-hint
 ></v-textarea>
 </div>
 </v-window-item>
 </v-window>

 <!-- Form Actions -->
 <div class="d-flex justify-end gap-4 mt-8 pt-6 border-t">
 <v-btn variant="text" class="text-none font-weight-bold">Cancel</v-btn>
 <v-btn 
 color="primary" 
 class="rounded-lg text-none font-weight-bold" 
 type="submit"
 :loading="loading"
 >
 Save Changes
 </v-btn>
 </div>

 </v-form>
 </div>
 </div>
 </v-card>
 </div>

 <v-snackbar v-model="snackbar" color="success" variant="flat" location="bottom right">
 Settings successfully updated!
 </v-snackbar>
 </v-container>
</template>

<style scoped>
.min-vh-100 { min-height: 100vh; }
.max-w-4xl { max-width: 900px; }
.gap-6 { gap: 24px; }
.gap-4 { gap: 16px; }

.bg-surface-light {
 background-color: rgba(var(--v-theme-surface), 0.5);
}

.border-e { border-right: 1px solid rgba(var(--v-border-color), 0.1); }
.border-t { border-top: 1px solid rgba(var(--v-border-color), 0.1); }

.font-family-sans {
 font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.sidebar-nav {
 min-width: 250px;
}

.motto-color-picker {
 height: 56px;
}
</style>

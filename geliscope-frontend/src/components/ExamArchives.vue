<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import axios from 'axios'

const router = useRouter()
const authStore = useAuthStore()

const loadingArchives = ref(false)
const loadingDocument = ref(false)
const archives = ref([])
const selectedArchiveId = ref(null)
const documentUrl = ref(null)
const documentMime = ref(null)

const userProfile = computed(() => authStore.userProfile || {})
const studentId = computed(() => userProfile.value?.student?.id)

onMounted(async () => {
 if (!authStore.isAuthenticated) {
 router.push('/login')
 return
 }

 if (studentId.value) {
 fetchArchives()
 }
})

const fetchArchives = async () => {
 try {
 loadingArchives.value = true
 const response = await axios.get(`/api/v1/archives/student/${studentId.value}`, {
 headers: { Authorization: `Bearer ${authStore.token}` }
 })
 archives.value = response.data.map(archive => ({
 title: `${archive.file_name} - ${new Date(archive.created_at).toLocaleDateString()}`,
 value: archive._id
 }))
 } catch (error) {
 console.error('Error fetching archives:', error)
 } finally {
 loadingArchives.value = false
 }
}

const loadDocument = async (archiveId) => {
 if (!archiveId) {
 documentUrl.value = null
 documentMime.value = null
 return
 }

 try {
 loadingDocument.value = true
 const response = await axios.get(`/api/v1/archives/${archiveId}/download`, {
 headers: { Authorization: `Bearer ${authStore.token}` }
 })
 
 const { mime_type, file_data } = response.data
 documentMime.value = mime_type
 documentUrl.value = `data:${mime_type};base64,${file_data}`
 } catch (error) {
 console.error('Error loading document:', error)
 } finally {
 loadingDocument.value = false
 }
}
</script>

<template>
 <v-container fluid class="py-8 h-100 d-flex flex-column" style="min-height: 90vh;">
 <!-- Top Header -->
 <v-card class="pa-6 rounded-3xl border bg-surface shadow-glow mb-6" elevation="4">
 <div class="d-flex align-center justify-space-between mb-4">
 <div>
 <h1 class="text-h4 font-weight-black d-flex align-center gap-3">
 <v-icon color="primary" size="36">mdi-archive-outline</v-icon>
 Exam Archives
 </h1>
 <p class="text-subtitle-1 text-medium-emphasis mt-2">
 Access and review your previously uploaded and evaluated exam papers.
 </p>
 </div>
 </div>
 
 <!-- Dropdown selector spanning full width -->
 <v-autocomplete
 v-model="selectedArchiveId"
 :items="archives"
 item-title="title"
 item-value="value"
 label="Select an Exam Paper"
 variant="outlined"
 color="primary"
 hide-details
 clearable
 :loading="loadingArchives"
 @update:model-value="loadDocument"
 class="mt-4"
 prepend-inner-icon="mdi-magnify"
 ></v-autocomplete>
 </v-card>

 <!-- Document Viewer Area -->
 <v-card class="flex-grow-1 rounded-3xl border bg-surface shadow-glow overflow-hidden d-flex flex-column" elevation="4">
 <div v-if="loadingDocument" class="d-flex flex-column align-center justify-center flex-grow-1 pa-8">
 <v-progress-circular indeterminate color="primary" size="64" class="mb-4"></v-progress-circular>
 <h3 class="text-h6 text-medium-emphasis">Decrypting and loading document...</h3>
 </div>
 
 <div v-else-if="documentUrl" class="d-flex flex-column flex-grow-1 w-100 h-100 relative">
 <div class="bg-surface-light border-b px-4 py-2 d-flex justify-space-between align-center">
 <span class="font-weight-bold text-caption text-uppercase text-medium-emphasis">Document Viewer</span>
 <v-btn 
 size="small" 
 variant="flat" 
 color="primary" 
 :href="documentUrl" 
 download="Exam_Paper"
 prepend-icon="mdi-download"
 >
 Download Original
 </v-btn>
 </div>
 
 <!-- Center Document Viewer -->
 <div class="flex-grow-1 w-100 bg-black d-flex align-center justify-center overflow-auto" style="min-height: 600px;">
 <!-- PDF Viewer -->
 <iframe 
 v-if="documentMime === 'application/pdf'"
 :src="documentUrl" 
 class="w-100 h-100 border-0" 
 title="Exam Paper Viewer"
 ></iframe>
 
 <!-- Image Viewer -->
 <img 
 v-else-if="documentMime?.startsWith('image/')"
 :src="documentUrl"
 alt="Exam Paper"
 class="max-w-100 h-auto"
 style="max-height: 100%; object-fit: contain;"
 />
 
 <div v-else class="text-center pa-8">
 <v-icon size="64" class="mb-4">mdi-file-question-outline</v-icon>
 <h3 class="text-h5">Unsupported File Format</h3>
 <p>Please use the download button above to view this file.</p>
 </div>
 </div>
 </div>
 
 <div v-else class="d-flex flex-column align-center justify-center flex-grow-1 pa-12 text-center">
 <v-icon size="80" color="muted" class="mb-6 opacity-50">mdi-file-document-outline</v-icon>
 <h2 class="text-h5 font-weight-bold text-medium-emphasis mb-2">No Document Selected</h2>
 <p class="text-body-1 text-muted">Use the dropdown above to search and select an exam paper from your archives.</p>
 </div>
 </v-card>
 </v-container>
</template>

<style scoped>
.shadow-glow {
 box-shadow: 0 4px 24px rgba(99, 102, 241, 0.08) !important;
}
.bg-surface {
 background-color: rgb(var(--v-theme-surface));
}
.bg-surface-light {
 background-color: rgba(var(--v-theme-surface), 0.5);
}
.bg-black {
 background-color: #1a1a1a;
}
</style>

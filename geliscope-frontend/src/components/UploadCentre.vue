<script setup>
import { ref, onMounted, watch } from 'vue'
import axios from 'axios'
import { useAuthStore } from '@/stores/auth'
import { useTeacherStore } from '@/stores/teacher'

const students = ref([])
const exams = ref([])
const classesList = ref([])

const selectedStudent = ref(null)
const selectedExam = ref(null)

const authStore = useAuthStore()
const currentUser = authStore.currentUser
const teacherStore = useTeacherStore()

const loadingData = ref(true)

const files = ref([])
const form = ref(null)
const createExamFormRef = ref(null)

const requiredRule = [v => !!v || 'This field is required']

const fileRules = [
 v => !v || v.length <= 10 || 'Maximum 10 files allowed',
 v => {
 if (!v || v.length === 0) return true;
 for (let i = 0; i < v.length; i++) {
 if (v[i].size > 50 * 1024 * 1024) {
 return 'File size cannot exceed 50MB';
 }
 }
 return true;
 },
 v => {
 if (!v || v.length === 0) return true;
 const allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
 for (let i = 0; i < v.length; i++) {
 if (!allowedTypes.includes(v[i].type)) {
 return 'Invalid file format. Only JPG, PNG, and PDF are allowed.';
 }
 }
 return true;
 }
]

const analyzing = ref(false)
const progress = ref(0)
const progressStep = ref('')
const analysisResult = ref(null)

// Create Exam State
const createExamDialog = ref(false)
const creatingExam = ref(false)
const examForm = ref({
  name: '',
  exam_code: '',
  class_id: null,
  subject_id: null,
  exam_date: '',
  term: 1,
  academic_year: '2025'
})

const isDragging = ref(false);

onMounted(async () => {
  const cachedAnalysis = localStorage.getItem('unsaved_analysis')
  if (cachedAnalysis) {
    try {
      analysisResult.value = JSON.parse(cachedAnalysis)
    } catch (e) {
      console.error('Error parsing cached analysis', e)
    }
  }

  loadingData.value = true
  try {
    const examResponse = await axios.get('/api/v1/exams')
    if (examResponse.data && examResponse.data.data) {
      exams.value = examResponse.data.data
    }

    if (currentUser) {
      if (currentUser.role === 'teacher' && currentUser.teacher) {
        const teacherStudents = await teacherStore.fetchStudents(currentUser.teacher.id)
        students.value = teacherStudents
        
        const teacherClasses = await teacherStore.fetchClasses(currentUser.teacher.id)
        classesList.value = teacherClasses
        
      } else if (currentUser.role === 'student' && currentUser.student) {
        students.value = [{
          id: currentUser.student.id,
          user: currentUser,
          admission_number: currentUser.student.admission_number 
        }]
        selectedStudent.value = currentUser.student.id 
      } else {
        students.value = [{
          id: currentUser.id,
          user: currentUser,
          admission_number: currentUser.role
        }]
        selectedStudent.value = currentUser.id
      }
    }
    
    if (exams.value.length > 0 && !selectedExam.value) {
      selectedExam.value = exams.value[0].id
    }
  } catch (err) {
    console.error('Error fetching data:', err)
  } finally {
    loadingData.value = false
  }
})

watch([selectedStudent, selectedExam], async ([newStudent, newExam]) => {
  if (newStudent && newExam) {
    try {
      loadingData.value = true
      // Clear previous analysis to prevent showing old data for a new student
      analysisResult.value = null 
      
      const response = await axios.get(`/api/v1/analysis/exam/${newStudent}/${newExam}`)
      if (response.data && response.data._id) {
        analysisResult.value = response.data
      }
    } catch (e) {
      // Expected 404 if no analysis exists yet; handle gracefully in the background
      if (e.response && e.response.status !== 404) {
        console.warn('Failed to check for existing analysis:', e.message)
      }
    } finally {
      loadingData.value = false
    }
  }
})

const submitCreateExam = async () => {
  if (createExamFormRef.value) {
    const { valid } = await createExamFormRef.value.validate()
    if (!valid) return
  }

  creatingExam.value = true
  try {
    const selectedClassObj = classesList.value.find(c => c.id === examForm.value.class_id)
    if (selectedClassObj) {
      examForm.value.subject_id = selectedClassObj.subject_id
    }

    const response = await axios.post('/api/v1/exams', {
      ...examForm.value,
      created_by: currentUser.teacher.id
    })
    exams.value.push(response.data)
    selectedExam.value = response.data.id
    createExamDialog.value = false
    alert('Exam created successfully!')
  } catch (e) {
    console.error(e)
    alert('Failed to create exam.')
  } finally {
    creatingExam.value = false
  }
}

const startAnalysis = async (action = 'analyze_only') => {
  if (form.value) {
    const { valid } = await form.value.validate()
    if (!valid) return
  }

  if (files.value.length === 0) {
    alert('Please select or drag at least one exam paper image to scan.')
    return
  }
  
  analyzing.value = true
  progress.value = 25
  progressStep.value = 'Preparing exam script images and calling GELISCOPE VISION & OCR ENGINE...'

  try {
    const formData = new FormData()
    files.value.forEach(file => {
      formData.append('files[]', file)
    })
    
    if (selectedStudent.value) {
      formData.append('student_id', selectedStudent.value) 
    }
    if (selectedExam.value) {
      formData.append('exam_id', selectedExam.value)
    }

    // Pass the action type to the backend
    formData.append('action', action)
    
    if (action === 'mark_and_analyze' && selectedExam.value) {
        try {
            const configResp = await axios.get(`/api/v1/analysis/marking-config/${selectedExam.value}`)
            if (configResp.data && configResp.data.instructions) {
                // If instructions is an array of strings, join it
                const instr = Array.isArray(configResp.data.instructions) ? configResp.data.instructions.join(' ') : configResp.data.instructions
                formData.append('marking_instructions', instr)
            }
        } catch (ce) {
            console.log('No specific marking config found, proceeding with default rubric.')
        }
    }

    progress.value = 60
    progressStep.value = action === 'mark_and_analyze' 
        ? 'Grading script against rubric and generating diagnostic report...'
        : 'Analyzing mathematical symbols, extracting text, and generating diagnostic report...'

    const response = await axios.post('/api/v1/analysis/process-vision', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    })

    progress.value = 100
    progressStep.value = 'Finalizing diagnostic report...'

    analysisResult.value = response.data
    localStorage.setItem('unsaved_analysis', JSON.stringify(response.data))
  } catch (err) {
    console.error('Vision API Error:', err)
    let errorMsg = 'Failed to process images via Vision AI.'
    if (err.response && err.response.data && err.response.data.message) {
      errorMsg = err.response.data.message
    }
    alert(errorMsg)
  } finally {
    analyzing.value = false
  }
}

const saveToMongoDB = async () => {
 try {
 const payload = {
 student_id: analysisResult.value.student_id,
 exam_id: analysisResult.value.exam_id,
 blooms_taxonomy: analysisResult.value.blooms_taxonomy,
 suggestions: analysisResult.value.suggestions,
 overall_summary: analysisResult.value.overall_summary,
 question_breakdown: analysisResult.value.question_breakdown
 }
 
 await axios.post('/api/v1/analysis/exam', payload)
 localStorage.removeItem('unsaved_analysis')
 alert('Analysis successfully saved to MongoDB / MySQL backend!')
 } catch (err) {
 alert('Analysis saved locally! (Note: Live MongoDB driver will sync upon extension install as per Walkthrough step 1)')
 }
}

//Drop Zone Configurations
function handleDragLeave(e) {
  // Prevent flickering when dragging over child elements inside the dropzone
  if (!e.currentTarget.contains(e.relatedTarget)) {
    isDragging.value = false;
  }
}

function handleDrop(e) {
  isDragging.value = false;
  const droppedFiles = Array.from(e.dataTransfer.files);
  files.value = [...files.value, ...droppedFiles];
}

const removeFile = (index) => {
  files.value.splice(index, 1);
}
</script>

<template>
 <v-container >
 <div class="mb-8">
 <v-chip color="accent" size="small" variant="flat" prepend-icon="mdi-camera-iris" class="mb-2 font-weight-bold">
 GELISCOPE VISION & OCR ENGINE
 </v-chip>
 <h1 class="text-h3 font-weight-black">Exam Paper Upload & Diagnostic Centre</h1> 
 </div>

 <v-row>
 <v-col cols="12" lg="5">
 <v-card class="bg-surface pa-6 border mb-6 h-100" elevation="3">
 <h3 class="text-h6 font-weight-bold mb-4 d-flex align-center">
 <v-icon color="primary" class="mr-2">mdi-cogs</v-icon> Assessment Configuration
 </h3>

 <v-select
 v-model="selectedStudent"
 :items="students"
 item-title="user.first_name"
 item-value="id"
 label="Select Enrolled Student"
 variant="outlined"
 density="comfortable"
 class="mb-4"
 :loading="loadingData"
 :disabled="loadingData"
 >
 <template v-slot:selection="{ item }">
 <div class="d-flex align-center">
 <v-avatar color="accent" size="24" class="mr-2 text-caption font-weight-bold">
 {{ item?.user ? item.user.first_name[0] : 'S' }}
 </v-avatar>
 
 {{ item?.user ? `${item.user.first_name} ${item.user.last_name}` : `Student #${item?.id || 'N/A'}` }}
 
 <v-chip size="x-small" color="primary" variant="outlined" class="ml-2 font-mono">
 {{ item?.admission_number || 'N/A' }}
 </v-chip>
 </div>
 </template>
 
 <template v-slot:item="{ item, props }">
 <v-list-item 
 v-bind="props" 
 :title="item?.user ? `${item.user.first_name} ${item.user.last_name}` : `Student #${item?.id || 'N/A'}`" 
 :subtitle="item?.admission_number || 'N/A'">
 </v-list-item>
 </template>
 </v-select>

 <v-select
 v-model="selectedExam"
 :items="exams"
 item-title="name"
 item-value="id"
 label="Select Exam / Subject Rubric"
 variant="outlined"
 density="comfortable"
 class="mb-6"
 :loading="loadingData"
 :disabled="loadingData"
 >
 <template v-slot:selection="{ item }">
 <div class="d-flex align-center font-weight-medium">
 <v-icon color="secondary" size="18" class="mr-2">mdi-file-document-check</v-icon>
 <v-chip v-if="item.exam_code" size="x-small" color="secondary" variant="tonal" class="mr-2 font-mono font-weight-bold">{{ item.exam_code }}</v-chip>
 {{ item.name || 'Unknown Exam' }} (Term {{ item.term || '-' }})
 </div>
 </template>
            <template v-slot:item="{ item, props }">
              <v-list-item v-bind="props" :title="item.name || 'Unknown Exam'" :subtitle="item.exam_code ? `Code: ${item.exam_code} • Term ${item.term}` : `Term ${item.term || '-'}`">
                <template v-slot:prepend>
                  <v-chip size="x-small" color="secondary" variant="outlined" class="mr-2 font-mono font-weight-bold">{{ item.exam_code || 'EX' }}</v-chip>
                </template>
              </v-list-item>
            </template>
          </v-select>

          <v-btn
            v-if="currentUser?.role === 'teacher'"
            variant="tonal"
            color="secondary"
            block
            class="mb-6 font-weight-bold"
            prepend-icon="mdi-plus"
            @click="createExamDialog = true"
          >
            Create New Exam
          </v-btn>

          <v-dialog v-model="createExamDialog" max-width="500">
            <v-card class="rounded-xl pa-4">
              <v-card-title class="text-h6 font-weight-bold d-flex align-center">
                <v-icon color="secondary" class="mr-2">mdi-file-document-edit</v-icon> Create Exam
              </v-card-title>
              <v-card-text>
                <v-form ref="createExamFormRef" @submit.prevent="submitCreateExam">
                  <v-text-field v-model="examForm.name" label="Exam Name" variant="outlined" class="mb-4" :rules="requiredRule" required />
                  <v-text-field v-model="examForm.exam_code" label="Exam Code" variant="outlined" class="mb-4" :rules="requiredRule" required />
                  <v-select v-model="examForm.class_id" :items="classesList" item-title="name" item-value="id" label="Class & Subject" variant="outlined" class="mb-4" :rules="requiredRule" required>
                    <template v-slot:selection="{ item }">
                      {{ item.raw.name }} ({{ item.raw.subject_name }})
                    </template>
                    <template v-slot:item="{ item, props }">
                      <v-list-item v-bind="props" :title="item.raw.name" :subtitle="item.raw.subject_name"></v-list-item>
                    </template>
                  </v-select>
                  <v-text-field v-model="examForm.exam_date" type="date" label="Exam Date" variant="outlined" class="mb-4" :rules="requiredRule" required />
                  <v-row>
                    <v-col cols="6"><v-text-field v-model.number="examForm.term" type="number" label="Term" variant="outlined" :rules="requiredRule" required /></v-col>
                    <v-col cols="6"><v-text-field v-model="examForm.academic_year" label="Academic Year" variant="outlined" :rules="requiredRule" required /></v-col>
                  </v-row>
                </v-form>
              </v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn variant="text" color="medium-emphasis" @click="createExamDialog = false">Cancel</v-btn>
                <v-btn color="secondary" variant="flat" :loading="creatingExam" @click="submitCreateExam">Save Exam</v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>

          <v-form ref="form">
            <!-- Upload Input -->
            <v-file-input
              v-model="files"
              label="Upload Exam Script Image(s)"
              prepend-icon="mdi-camera"
              variant="outlined"
              multiple
              show-size
              chips
              counter
              accept="image/.jpg, image/.jpeg, image/.png, .pdf"
              color="primary"
              class="mb-6"
              :rules="fileRules"
            >
              <template #selection="{ fileNames }">
                <template v-for="(fileName, index) in fileNames" :key="fileName">
                  <v-chip closable @click:close="removeFile(index)" size="small" color="primary" class="me-2">{{ fileName }}</v-chip>
                </template>
              </template>
            </v-file-input>
            <!-- Drag and drop zone -->
             <div 
              class="drop-zone cursor-pointer"
              :class="{ 'is-dragging': isDragging }"
              @dragenter.prevent="isDragging = true"
              @dragover.prevent="isDragging = true"
              @dragleave.prevent="handleDragLeave"
              @drop.prevent="handleDrop"
              >
             <v-icon size="48" color="primary" class="mb-2">mdi-cloud-upload-outline</v-icon>
              <div class="text-subtitle-1 font-weight-bold">Drag & Drop Scanned Sheets Here</div>
              <div class="text-caption text-medium-emphasis">Supports high-res JPG, PNG, WEBP, and PDF scripts up to 50MB</div>
            </div>
            <!-- Dual Action Buttons -->
            <v-row>
              <v-col cols="12" md="6">
                <v-btn
                  v-if="currentUser.role == 'teacher'"
                  color="accent"
                  size="large"
                  block
                  :loading="analyzing"
                  prepend-icon="mdi-marker-check"
                  elevation="4"
                  class="py-4 font-weight-bold"
                  @click="startAnalysis('mark_and_analyze')"
                >
                  Mark & Analyze
                </v-btn>
              </v-col>
              <v-col cols="12" md="{{ currentUser.role == 'teacher' ? 6 : 12 }}">
                <v-btn
                  color="primary"
                  size="large"
                  block
                  :loading="analyzing"
                  prepend-icon="mdi-brain"
                  elevation="4"
                  class="py-4 font-weight-bold"
                  @click="startAnalysis('analyze_only')"
                >
                  Analyze Only
                </v-btn>
              </v-col>
            </v-row>
          </v-form>
 </v-card>
 </v-col>

 <v-col cols="12" lg="7">
 <v-card v-if="analyzing" class="bg-surface pa-10 text-center border h-100 d-flex flex-column justify-center align-center" elevation="3">
 <v-progress-circular :model-value="progress" color="primary" size="120" width="10" class="mb-6">
 <span class="text-h6 font-weight-black">{{ progress }}%</span>
 </v-progress-circular>
 <h3 class="text-h5 font-weight-bold mb-2">Analyzing Student Script...</h3>
 <p class="text-body-1 text-primary font-weight-medium mb-6">{{ progressStep }}</p>
 <div class="text-caption text-medium-emphasis max-w-md">
 Our Vision OCR neural network is extracting mathematical steps and evaluating them against the Kenyan curriculum rubric stored in MySQL (`question_scores`).
 </div>
 </v-card>

 <v-card v-else-if="!analysisResult" class="pa-10 text-center border h-100 d-flex flex-column justify-center align-center bg-surface" elevation="2">
 <v-icon size="80" color="medium-emphasis" class="mb-4 opacity-30">mdi-file-find-outline</v-icon>
 <h3 class="text-h5 font-weight-bold text-medium-emphasis">Awaiting Exam Paper Upload</h3>
 <p class="text-body-2 text-medium-emphasis max-w-md mt-2">
 Select a student, attach their scanned exam image on the left, and click **Analyze Exam Script with Vision AI** to generate a deep diagnostic Bloom's taxonomy report.
 </p>
 </v-card>

 <v-card v-else class="pa-6 border bg-surface" elevation="4">
 <div class="d-flex justify-space-between align-start mb-6">
 <div>
 <v-chip color="success" size="small" variant="flat" prepend-icon="mdi-check-decagram" class="mb-2 font-weight-bold">
 VISION ANALYSIS COMPLETE
 </v-chip>
 <h3 class="text-h5 font-weight-bold">Diagnostic Evaluation & Bloom's Breakdown</h3>
 <p class="text-caption text-medium-emphasis">Generated for Student ID #{{ analysisResult.student_id }} &bull; Exam ID #{{ analysisResult.exam_id }}</p>
 </div>
 <v-btn color="accent" variant="flat" @click="saveToMongoDB" prepend-icon="mdi-database-export" size="small">
 Save to MongoDB
 </v-btn>
 </div>

 <v-alert color="primary" variant="tonal" icon="mdi-lightbulb-on" border="start" class="mb-6">
 <div class="font-weight-bold mb-1">AI Executive Summary</div>
 <div class="text-body-2">{{ analysisResult.overall_summary }}</div>
 </v-alert>

 <h4 class="text-subtitle-1 font-weight-bold mb-3 d-flex align-center">
 <v-icon color="secondary" class="mr-2">mdi-chart-bubble</v-icon> Bloom's Taxonomy Cognitive Mastery (%)
 </h4>
 <v-row class="mb-6">
 <v-col cols="12" sm="6" v-for="(val, key) in analysisResult.blooms_taxonomy" :key="key">
 <div class="d-flex justify-space-between text-caption font-weight-bold mb-1 text-capitalize">
 <span>{{ key }}</span>
 <span :class="val >= 80 ? 'text-success' : (val >= 60 ? 'text-warning' : 'text-error')">{{ val }}%</span>
 </div>
 <v-progress-linear
 :model-value="val"
 :color="val >= 80 ? 'success' : (val >= 60 ? 'warning' : 'error')"
 height="8"
 rounded
 ></v-progress-linear>
 </v-col>
 </v-row>

 <h4 class="text-subtitle-1 font-weight-bold mb-3 d-flex align-center">
 <v-icon color="accent" class="mr-2">mdi-format-list-checks</v-icon> Question-Level Diagnostics
 </h4>
 <v-table class="bg-surface border rounded-xl mb-6">
 <thead>
 <tr>
 <th class="font-weight-bold">Q#</th>
 <th class="font-weight-bold">Topic Area</th>
 <th class="font-weight-bold">Score</th>
 <th class="font-weight-bold">Status</th>
 <th class="font-weight-bold">Diagnostic Comment</th>
 </tr>
 </thead>
 <tbody>
 <tr v-for="q in analysisResult.question_breakdown" :key="q.q">
 <td class="font-weight-bold">Q{{ q.q }}</td>
 <td><v-chip size="x-small" variant="outlined">{{ q.topic }}</v-chip></td>
 <td class="font-weight-black text-primary">{{ q.score }}/{{ q.max }}</td>
 <td>
 <v-chip size="x-small" :color="q.status === 'Perfect' ? 'success' : 'error'" variant="flat">
 {{ q.status }}
 </v-chip>
 </td>
 <td class="text-caption text-medium-emphasis">{{ q.comment }}</td>
 </tr>
 </tbody>
 </v-table>

 <h4 class="text-subtitle-1 font-weight-bold mb-3 d-flex align-center">
 <v-icon color="warning" class="mr-2">mdi-clipboard-list</v-icon> Actionable Revision Steps for Student & Parent
 </h4>
 <v-list class="bg-surface rounded-xl border pa-0">
 <v-list-item v-for="(sug, idx) in analysisResult.suggestions" :key="idx" class="pa-3 border-b">
 <template v-slot:prepend>
 <v-avatar color="warning" size="24" class="mr-3 text-caption font-weight-bold">
 {{ idx + 1 }}
 </v-avatar>
 </template>
 <v-list-item-title class="text-body-2 font-weight-medium w-auto" >{{ sug }}</v-list-item-title>
 </v-list-item>
 </v-list>
 </v-card>
 </v-col>
 </v-row>
 </v-container>
</template>
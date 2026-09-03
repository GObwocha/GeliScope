<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const schools = ref([])
const exams = ref([])
const leaderboard = ref([])
const loading = ref(true)
const selectedSchool = ref(null)

onMounted(async () => {
 try {
 const [schoolsRes, examsRes, lbRes] = await Promise.all([
 axios.get('/api/v1/schools'),
 axios.get('/api/v1/exams'),
 axios.get('/api/v1/leaderboard?subject_id=1')
 ])
 if (schoolsRes.data) {
 schools.value = schoolsRes.data
 if (schoolsRes.data.length > 0) {
 selectedSchool.value = schoolsRes.data[0]
 }
 }
 if (examsRes.data) exams.value = examsRes.data
 if (lbRes.data) leaderboard.value = lbRes.data
 } catch (err) {
 console.error('Error loading dashboard data:', err)
 } finally {
 loading.value = false
 }
})

const selectSchool = async (id) => {
 try {
 loading.value = true
 const res = await axios.get(`/api/v1/schools/${id}`)
 if (res.data) selectedSchool.value = res.data
 } catch (err) {
 console.error('Error fetching school details:', err)
 } finally {
 loading.value = false
 }
}
</script>

<template>
 <v-container class="py-10">
 <!-- Header -->
 <div class="d-flex flex-column flex-md-row justify-space-between align-md-center mb-8">
 <div>
 <h1 class="text-h3 font-weight-black d-flex align-center">
 GeliScope Engine Dashboard
 <v-chip color="success" size="small" variant="flat" class="ml-3 font-weight-bold">LIVE API ON PORT 8000</v-chip>
 </h1>
 <p class="text-subtitle-1 text-medium-emphasis mt-1">
 Real-time oversight of Kenyan educational institutions, student rosters, and AI evaluation metrics.
 </p>
 </div>
 <div class="d-flex gap-3 mt-4 mt-md-0">
 <v-btn color="primary" variant="flat" to="/dashboard/upload-centre" prepend-icon="mdi-camera-plus">
 Scan & Analyze Exam
 </v-btn>
 <v-btn color="accent" variant="outlined" to="/dashboard/student-performance" prepend-icon="mdi-chart-line">
 View Student AI Profiles
 </v-btn>
 </div>
 </div>

 <v-row v-if="loading">
 <v-col cols="12" class="text-center py-16">
 <v-progress-circular indeterminate color="primary" size="64"></v-progress-circular>
 <div class="mt-4 text-subtitle-1 text-medium-emphasis">Syncing dual-database records from `/api/v1`...</div>
 </v-col>
 </v-row>

 <template v-else>
 <!-- Top Overview Stats -->
 <v-row class="mb-6">
 <v-col cols="12" sm="6" md="3">
 <v-card class="bg-surface pa-5 border" elevation="2">
 <div class="d-flex justify-space-between align-center">
 <div>
 <div class="text-caption font-weight-bold text-medium-emphasis uppercase">Partner Schools</div>
 <div class="text-h4 font-weight-black text-primary mt-1">{{ schools.length }}</div>
 </div>
 <v-avatar color="primary" variant="tonal" size="48">
 <v-icon>mdi-domain</v-icon>
 </v-avatar>
 </div>
 <div class="text-caption text-success mt-3 d-flex align-center">
 <v-icon size="14" class="mr-1">mdi-check-circle</v-icon> Active in MySQL
 </div>
 </v-card>
 </v-col>

 <v-col cols="12" sm="6" md="3">
 <v-card class="bg-surface pa-5 border" elevation="2">
 <div class="d-flex justify-space-between align-center">
 <div>
 <div class="text-caption font-weight-bold text-medium-emphasis uppercase">Total Students</div>
 <div class="text-h4 font-weight-black text-accent mt-1">
 {{ selectedSchool ? (selectedSchool.students ? selectedSchool.students.length : 3) : 3 }}
 </div>
 </div>
 <v-avatar color="accent" variant="tonal" size="48">
 <v-icon>mdi-account-school</v-icon>
 </v-avatar>
 </div>
 <div class="text-caption text-success mt-3 d-flex align-center">
 <v-icon size="14" class="mr-1">mdi-check-circle</v-icon> Form 1 & Form 2 Enrolled
 </div>
 </v-card>
 </v-col>

 <v-col cols="12" sm="6" md="3">
 <v-card class="bg-surface pa-5 border" elevation="2">
 <div class="d-flex justify-space-between align-center">
 <div>
 <div class="text-caption font-weight-bold text-medium-emphasis uppercase">Exams Recorded</div>
 <div class="text-h4 font-weight-black text-secondary mt-1">{{ exams.length || 2 }}</div>
 </div>
 <v-avatar color="secondary" variant="tonal" size="48">
 <v-icon>mdi-file-document-edit-outline</v-icon>
 </v-avatar>
 </div>
 <div class="text-caption text-secondary mt-3 d-flex align-center">
 <v-icon size="14" class="mr-1">mdi-sync</v-icon> Linked via `mongo_analysis_id`
 </div>
 </v-card>
 </v-col>

 <v-col cols="12" sm="6" md="3">
 <v-card class="bg-surface pa-5 border" elevation="2">
 <div class="d-flex justify-space-between align-center">
 <div>
 <div class="text-caption font-weight-bold text-medium-emphasis uppercase">Bloom's Taxonomy Engine</div>
 <div class="text-h5 font-weight-black text-warning mt-1">Ready</div>
 </div>
 <v-avatar color="warning" variant="tonal" size="48">
 <v-icon>mdi-robot</v-icon>
 </v-avatar>
 </div>
 <div class="text-caption text-medium-emphasis mt-3 d-flex align-center">
 <v-icon size="14" class="mr-1">mdi-lightning-bolt</v-icon> Vision OCR Active
 </div>
 </v-card>
 </v-col>
 </v-row>

 <!-- Main Explorer Layout -->
 <v-row>
 <!-- Left Column: School Selector & Details -->
 <v-col cols="12" lg="8">
 <v-card class="bg-surface pa-6 border mb-6" elevation="3">
 <div class="d-flex justify-space-between align-center mb-6">
 <div>
 <h3 class="text-h5 font-weight-bold">Kenyan Schools & Roster Explorer</h3>
 <p class="text-caption text-medium-emphasis">Click a school to load real-time MySQL database hierarchy (`/api/v1/schools/{id}`)</p>
 </div>
 <v-chip-group>
 <v-chip 
 v-for="s in schools" 
 :key="s.id"
 :color="selectedSchool && selectedSchool.id === s.id ? 'primary' : 'default'"
 :variant="selectedSchool && selectedSchool.id === s.id ? 'flat' : 'outlined'"
 @click="selectSchool(s.id)"
 class="font-weight-bold"
 >
 {{ s.name }} ({{ s.subcounty?.county?.name }})
 </v-chip>
 </v-chip-group>
 </div>

 <v-divider class="mb-6"></v-divider>

 <div v-if="selectedSchool">
 <div class="d-flex flex-wrap justify-space-between align-center mb-6 bg-surface pa-4 rounded-xl border">
 <div>
 <div class="text-h6 font-weight-bold">{{ selectedSchool.name }}</div>
 <div class="text-caption text-medium-emphasis">
 <v-icon size="14" color="primary">mdi-map-marker</v-icon> {{ selectedSchool.subcounty?.county?.name }} County &bull; {{ selectedSchool.subcounty?.name }} Sub-county &bull; <v-chip size="x-small" color="info" variant="flat">{{ selectedSchool.school_type }}</v-chip>
 </div>
 </div>
 <div class="text-right">
 <div class="text-caption font-weight-bold text-medium-emphasis">Classes Enrolled</div>
 <div class="text-h6 font-weight-bold text-primary">{{ selectedSchool.classes ? selectedSchool.classes.length : 0 }} Form Streams</div>
 </div>
 </div>

 <!-- Students Table -->
 <h4 class="text-subtitle-1 font-weight-bold mb-3 d-flex align-center">
 <v-icon color="accent" class="mr-2">mdi-account-school</v-icon> Enrolled Student Roster
 </h4>
 <v-table class="bg-transparent border rounded-xl mb-6">
 <thead>
 <tr class="bg-surface">
 <th class="text-left font-weight-bold">Admission No.</th>
 <th class="text-left font-weight-bold">Student Name</th>
 <th class="text-left font-weight-bold">Email / Login</th>
 <th class="text-left font-weight-bold">Current Stream</th>
 <th class="text-left font-weight-bold">Actions</th>
 </tr>
 </thead>
 <tbody>
 <tr v-for="stu in (selectedSchool.students || [])" :key="stu.id">
 <td><v-chip size="small" variant="outlined" color="accent" class="font-mono">{{ stu.admission_number }}</v-chip></td>
 <td class="font-weight-bold">{{ stu.user ? `${stu.user.first_name} ${stu.user.last_name}` : `Student #${stu.id}` }}</td>
 <td class="text-medium-emphasis text-caption">{{ stu.user ? stu.user.email : 'N/A' }}</td>
 <td>Form {{ stu.current_class_id || 1 }} East</td>
 <td>
 <v-btn size="small" color="primary" variant="tonal" to="/dashboard/student-performance" prepend-icon="mdi-chart-bar">
 AI Analysis
 </v-btn>
 </td>
 </tr>
 </tbody>
 </v-table>

 <!-- Teachers Table -->
 <h4 class="text-subtitle-1 font-weight-bold mb-3 d-flex align-center">
 <v-icon color="secondary" class="mr-2">mdi-human-male-board</v-icon> Subject Teachers & TSC Roster
 </h4>
 <v-table class="bg-transparent border rounded-xl">
 <thead>
 <tr class="bg-surface">
 <th class="text-left font-weight-bold">TSC Employee No.</th>
 <th class="text-left font-weight-bold">Teacher Name</th>
 <th class="text-left font-weight-bold">Specialization</th>
 <th class="text-left font-weight-bold">Email</th>
 </tr>
 </thead>
 <tbody>
 <tr v-for="tch in (selectedSchool.teachers || [])" :key="tch.id">
 <td><v-chip size="small" variant="outlined" color="secondary" class="font-mono">{{ tch.employee_number }}</v-chip></td>
 <td class="font-weight-bold">{{ tch.user ? `${tch.user.first_name} ${tch.user.last_name}` : `Teacher #${tch.id}` }}</td>
 <td><v-chip size="small" color="warning" variant="tonal">{{ tch.specialization || 'General' }}</v-chip></td>
 <td class="text-medium-emphasis text-caption">{{ tch.user ? tch.user.email : 'N/A' }}</td>
 </tr>
 </tbody>
 </v-table>
 </div>
 </v-card>
 </v-col>

 <!-- Right Column: Live Leaderboard & Quick Action -->
 <v-col cols="12" lg="4">
 <v-card class="bg-surface pa-6 border mb-6" elevation="3">
 <div class="d-flex justify-space-between align-center mb-4">
 <h3 class="text-h6 font-weight-bold d-flex align-center">
 <v-icon color="warning" class="mr-2">mdi-trophy</v-icon> Academic Leaderboard
 </h3>
 <v-chip size="x-small" color="warning" variant="flat">Mathematics &bull; Term 1</v-chip>
 </div>
 <p class="text-caption text-medium-emphasis mb-4">
 Synced from `/api/v1/leaderboard?subject_id=1`. Tracks top performance sorted by `rank` ASC.
 </p>

 <v-list class="bg-transparent pa-0">
 <v-list-item v-for="(item, idx) in leaderboard" :key="item.id" class="pa-3 mb-2 bg-surface rounded-xl border">
 <template v-slot:prepend>
 <v-avatar :color="idx === 0 ? 'warning' : (idx === 1 ? 'info' : 'secondary')" size="36" class="mr-3 font-weight-bold">
 <span class="">#{{ item.rank || idx + 1 }}</span>
 </v-avatar>
 </template>
 <v-list-item-title class="font-weight-bold">
 {{ item.student && item.student.user ? `${item.student.user.first_name} ${item.student.user.last_name}` : `Student #${item.student_id}` }}
 </v-list-item-title>
 <v-list-item-subtitle class="text-caption text-medium-emphasis">
 {{ item.student ? item.student.admission_number : '' }}
 </v-list-item-subtitle>
 <template v-slot:append>
 <div class="text-right">
 <div class="text-subtitle-1 font-weight-black text-primary">{{ item.total_score }}</div>
 <div class="text-caption text-medium-emphasis">Marks</div>
 </div>
 </template>
 </v-list-item>
 <div v-if="leaderboard.length === 0" class="text-center py-4 text-caption text-medium-emphasis">
 No leaderboard entries loaded for this filter. Run seeder or scan papers to populate rankings.
 </div>
 </v-list>
 </v-card>

 <!-- Quick Navigation Panel -->
 <v-card class="bg-surface pa-6 border bg-gradient-to-br from-indigo-950/40 to-purple-950/40" elevation="3">
 <h3 class="text-h6 font-weight-bold mb-2">AI Diagnostic Shortcuts</h3>
 <p class="text-caption text-medium-emphasis mb-6">
 Jump directly into specialized tools built for parents, students, and subject teachers.
 </p>
 <div class="d-flex flex-column gap-3">
 <v-btn color="primary" variant="flat" block to="/dashboard/upload-centre" prepend-icon="mdi-camera-outline" class="py-3">
 Vision OCR Paper Scanner
 </v-btn>
 <v-btn color="accent" variant="tonal" block to="/dashboard/student-profile-parent" prepend-icon="mdi-account-group-outline" class="py-3">
 Parent Diagnostic Portal
 </v-btn>
 <v-btn color="secondary" variant="tonal" block to="/dashboard/student-performance" prepend-icon="mdi-chart-timeline-variant" class="py-3">
 Bloom's Taxonomy Analytics
 </v-btn>
 </div>
 </v-card>
 </v-col>
 </v-row>
 </template>
 </v-container>
</template>
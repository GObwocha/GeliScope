import { defineStore } from 'pinia'
import axios from 'axios'
import { ref } from 'vue'

export const useTeacherStore = defineStore('teacher', () => {
  const students = ref([])
  const classes = ref([])
  const loading = ref(false)

  const fetchStudents = async (teacherId) => {
    // Return early if already fetched
    if (students.value.length > 0) return students.value

    loading.value = true
    try {
      const response = await axios.get(`/api/v1/analysis/teacher/${teacherId}/students`)
      students.value = response.data
    } catch (error) {
      console.error('Failed to fetch teacher students:', error)
    } finally {
      loading.value = false
    }
    
    return students.value
  }

  const fetchClasses = async (teacherId) => {
    // Return early if already fetched
    if (classes.value.length > 0) return classes.value

    loading.value = true
    try {
      const response = await axios.get(`/api/v1/analysis/teacher/${teacherId}/classes`)
      classes.value = response.data
    } catch (error) {
      console.error('Failed to fetch teacher classes:', error)
    } finally {
      loading.value = false
    }
    
    return classes.value
  }

  const clearStore = () => {
    students.value = []
    classes.value = []
  }

  return {
    students,
    classes,
    loading,
    fetchStudents,
    fetchClasses,
    clearStore
  }
})

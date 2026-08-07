import axios from 'axios'

export default {
  async getStudents(teacherId) {
    const response = await axios.get(`/api/v1/analysis/teacher/${teacherId}/students`)
    return response.data
  },

  async getClasses(teacherId) {
    const response = await axios.get(`/api/v1/analysis/teacher/${teacherId}/classes`)
    return response.data
  }
}

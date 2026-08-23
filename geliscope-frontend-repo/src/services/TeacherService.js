import axios from 'axios'

export default {
  async getStudents(teacherId, classId = null) {
    const url = classId 
      ? `/api/v1/analysis/teacher/${teacherId}/students?class_id=${classId}`
      : `/api/v1/analysis/teacher/${teacherId}/students`;
    const response = await axios.get(url);
    return response.data;
  },

  async getClasses(teacherId) {
    const response = await axios.get(`/api/v1/analysis/teacher/${teacherId}/classes`)
    return response.data
  }
}

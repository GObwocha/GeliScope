import axios from 'axios'

export default {
  async getTodos(studentId) {
    const response = await axios.get(`/api/v1/students/${studentId}/todos`)
    return response.data
  },

  async createTodo(studentId, title) {
    const response = await axios.post(`/api/v1/students/${studentId}/todos`, {
      title,
      is_completed: false
    })
    return response.data
  },

  async updateTodo(todoId, updates) {
    const response = await axios.put(`/api/v1/todos/${todoId}`, updates)
    return response.data
  },

  async deleteTodo(todoId) {
    const response = await axios.delete(`/api/v1/todos/${todoId}`)
    return response.data
  }
}

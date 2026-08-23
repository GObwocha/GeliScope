import { useQuery } from '@tanstack/vue-query'
import axios from 'axios'

export function useTeacherStudents(teacherId) {
  return useQuery({
    queryKey: ['teacher', teacherId, 'students'],
    queryFn: async () => {
      const response = await axios.get(`/api/v1/analysis/teacher/${teacherId}/students`)
      return response.data
    },
    enabled: !!teacherId // Only fetch if teacherId is present
  })
}

export function useTeacherClasses(teacherId) {
  return useQuery({
    queryKey: ['teacher', teacherId, 'classes'],
    queryFn: async () => {
      const response = await axios.get(`/api/v1/analysis/teacher/${teacherId}/classes`)
      return response.data
    },
    enabled: !!teacherId
  })
}

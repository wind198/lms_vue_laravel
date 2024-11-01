import { useQuery } from '@tanstack/vue-query'
import useApiHttpClient from './useHttpClient'
import { apiPrefix } from '../utils/helpers'
import { IPaginatedData } from '../types/common.type'
import { IStudent } from '../types/entities/student.entity'

type IOptions = {
  page: number
  per_page: number
  order: string
  order_by: string
}

export default function useStudents(options: IOptions) {
  const { order, order_by, page, per_page } = options

  const { $get } = useApiHttpClient()

  const data = useQuery({
    queryKey: ['students', { order, order_by, page, per_page }],
    queryFn: async () => {
      const { data } = await $get<IPaginatedData<IStudent>>(
        apiPrefix('students'),
        {
          params: {
            order,
            order_by,
            page,
            per_page,
          },
        }
      )
      return data
    },
  })

  return data
}

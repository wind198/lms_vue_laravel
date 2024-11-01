import { useQuery, keepPreviousData } from '@tanstack/vue-query'
import useApiHttpClient from './useHttpClient'
import { apiPrefix } from '../utils/helpers'
import { IPaginatedData } from '../types/common.type'
import { IStudent } from '../types/entities/student.entity'

type IOptions = {
  page: Ref<number>
  per_page: Ref<number>
  order: Ref<string>
  order_by: Ref<string>
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
            order: order.value,
            order_by: order_by.value,
            page: page.value,
            per_page: per_page.value,
          },
        }
      )
      return data
    },
    placeholderData: keepPreviousData,
  })

  return data
}

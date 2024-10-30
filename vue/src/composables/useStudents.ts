import { useQuery } from '@tanstack/vue-query'
import useApiHttpClient from './useHttpClient'
import { apiPrefix } from '../utils/helpers'

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
    queryFn: () =>
      $get(apiPrefix('students'), {
        params: {
          order,
          order_by,
          page,
          per_page,
        },
      }),
  })

  return data
}

import { keepPreviousData, useQuery } from '@tanstack/vue-query'
import { AxiosError } from 'axios'
import dayjs from 'dayjs'
import { set } from 'lodash-es'
import { IPaginatedData, IQueryListParamsAsRefs } from '../types/common.type'
import { IStudent } from '../types/entities/student.entity'
import { apiPrefix } from '../utils/helpers'
import useApiHttpClient from './useHttpClient'
import {
  DEFAULT_ORDER,
  DEFAULT_ORDER_BY,
  DEFAULT_PAGE,
  DEFAULT_PER_PAGE,
} from '../utils/constants'
import { IGeneration } from '../types/entities/generation.entity'

type IOptions = Partial<IQueryListParamsAsRefs>

export default function useGenerations(options?: IOptions) {
  const { order, order_by, page, per_page, filter = {} } = options ?? {}

  const { $get } = useApiHttpClient()

  const fetchGenerations = async () => {
    const augmentedFilter = {} as Record<string, any>
    for (const k in filter.value) {
      const v = filter.value[k]
      if (['created_at'].includes(k)) {
        const { gte, lte } = v
        Object.entries({ gte, lte })
          .filter((x) => x[1])
          .forEach(([x, y]) => {
            if (x === 'gte') {
              y = dayjs(y).startOf('day').toDate()
            } else {
              y = dayjs(y).endOf('day').toDate()
            }
            set(augmentedFilter, [k, x], y)
          })
      } else {
        augmentedFilter[k] = v
      }
    }
    const apiRoute = 'generations'
    const { data } = await $get<IPaginatedData<IGeneration>>(
      apiPrefix(apiRoute),
      {
        params: {
          order: order?.value ?? DEFAULT_ORDER,
          order_by: order_by?.value ?? DEFAULT_ORDER_BY,
          page: page?.value ?? DEFAULT_PAGE,
          per_page: per_page?.value ?? DEFAULT_PER_PAGE,
          filter: augmentedFilter ?? {},
        },
      }
    )
    return data
  }

  const data = useQuery<
    IPaginatedData<IGeneration>,
    AxiosError,
    IPaginatedData<IGeneration>
  >({
    queryKey: [
      'generations',
      {
        filter,
        order,
        order_by,
        page,
        per_page,
      },
    ],
    queryFn: fetchGenerations,
    placeholderData: keepPreviousData,
  })

  return { ...data }
}

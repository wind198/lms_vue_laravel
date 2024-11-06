import {
  useQuery,
  keepPreviousData,
  QueryFunctionContext,
} from '@tanstack/vue-query'
import useApiHttpClient from './useHttpClient'
import { apiPrefix } from '../utils/helpers'
import { IOrder, IPaginatedData, IQueryListParams } from '../types/common.type'
import { IStudent } from '../types/entities/student.entity'
import { isEmpty, set } from 'lodash-es'
import {
  DEFAULT_ORDER,
  DEFAULT_ORDER_BY,
  DEFAULT_PAGE,
  DEFAULT_PER_PAGE,
} from '../utils/constants'
import { parse } from 'qs'
import { AxiosError } from 'axios'
import dayjs from 'dayjs'
import { IUser, IUserType } from '../types/entities/user.entity'

type IOptions = {
  user_type: IUserType
  page: Ref<number>
  per_page: Ref<number>
  order: Ref<IOrder>
  order_by: Ref<string>
  filter: Ref<Record<string, any>>
}

export default function useUsers(options: IOptions) {
  const { order, order_by, page, per_page, filter, user_type } = options

  const { $get } = useApiHttpClient()

  const fetchStudents = async () => {
    const augmentedFilter = {} as Record<string, any>
    for (const k in filter.value) {
      const v = filter.value[k]
      if (['created_at', 'dob'].includes(k)) {
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
    let apiRoute
    switch (user_type) {
      case 'admin':
        apiRoute = 'admins'
        break

      case 'teacher':
        apiRoute = 'teachers'
        break

      default:
        apiRoute = 'students'
    }
    const { data } = await $get<IPaginatedData<IStudent>>(apiPrefix(apiRoute), {
      params: {
        order: order.value,
        order_by: order_by.value,
        page: page.value,
        per_page: per_page.value,
        filter: augmentedFilter,
      },
    })
    return data
  }

  const data = useQuery<
    IPaginatedData<IStudent>,
    AxiosError,
    IPaginatedData<IStudent>
  >({
    queryKey: [
      user_type + 's',
      {
        filter,
        order,
        order_by,
        page,
        per_page,
      },
    ],
    queryFn: fetchStudents,
    placeholderData: keepPreviousData,
  })

  return { ...data }
}

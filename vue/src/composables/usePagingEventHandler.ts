import { Ref } from 'vue'
import { IOrder } from '../types/common.type'
import { DEFAULT_ORDER, DEFAULT_ORDER_BY } from '../utils/constants'
import useQueryParamsStore from '../stores/query'
import useUpdateSearchParamsAndNavigate from './useUpdateSearchParamsAndNavigate'
import { merge } from 'lodash-es'

// type IProps<T> = {
//     data: T[];
//     params: IServerTableParams<T>;
//     headers: any[];
// };

export default function useServerTable<T>() {
  const { $push } = useUpdateSearchParamsAndNavigate()

  const { searchParams } = useQueryParamsStore()

  const handleChangeOrder = (key?: string, $newOrder?: IOrder) => {
    const newOrderBy = key || DEFAULT_ORDER_BY
    const newOrder: IOrder = $newOrder || DEFAULT_ORDER
    $push({
      order: newOrder,
      order_by: newOrderBy,
    })
  }

  const handleChangePage = (v: number) => {
    $push({ page: v })
  }
  const handleChangePerPage = (v: number) => {
    $push({ per_page: v })
  }

  const handleChangeFilter = (newFilters: Record<string, any>) => {
    const currentFilter = searchParams.filter ?? {}

    const newFilter = merge(currentFilter, newFilters)

    $push({ filter: newFilter })
  }

  return {
    handleChangeFilter,
    handleChangePage,
    handleChangeOrder,
    handleChangePerPage,
  }
}

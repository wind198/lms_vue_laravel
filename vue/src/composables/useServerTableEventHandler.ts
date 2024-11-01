import useQueryParamsStore, { DefaultSearchParams } from '../stores/query'
import { IOrder } from '../types/common.type'
import { reserveOrder } from '../utils/helpers'
import useUpdateSearchParamsAndNavigate from './useUpdateSearchParamsAndNavigate'

export default function useServerTableEventHandler() {
  const { searchParams } = useQueryParamsStore()

  const { $push } = useUpdateSearchParamsAndNavigate()

  const handleUpdateSort = (v: any) => {
    if (!v.length) {
      $push({
        order: DefaultSearchParams.order,
        order_by: DefaultSearchParams.order_by,
      })
      return
    }
    const { key: orderBy, order } = v[0]

    $push({
      order,
      order_by: orderBy,
    })
  }

  const handleUpdatePage = (v: number) => {
    $push({ page: v })
  }
  const handleUpdatePerPage = (v: number) => {
    $push({ per_page: v })
  }

  return { handleUpdateSort, handleUpdatePage, handleUpdatePerPage }
}

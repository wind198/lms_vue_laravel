import useQueryParamsStore, { DefaultSearchParams } from '../stores/query'
import { IOrder } from '../types/common.type'
import { reserveOrder } from '../utils/helpers'
import useUpdateSearchParamsAndReload from './useUpdateSearchParamsAndReload'

export default function useServerTableEventHandler() {
  const { searchParams } = useQueryParamsStore()

  const { updateSearchParamsAndReload } = useUpdateSearchParamsAndReload()

  const handleUpdateSort = (v: any) => {
    if (!v.length) {
      updateSearchParamsAndReload({
        order: reserveOrder(DefaultSearchParams.order),
        order_by: DefaultSearchParams.order_by,
      })
      return
    }
    const { key: orderBy, order } = v[0]

    updateSearchParamsAndReload({
      order,
      order_by: orderBy,
    })
  }

  return { handleUpdateSort }
}

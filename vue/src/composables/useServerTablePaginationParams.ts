import { storeToRefs } from 'pinia'
import useQueryParamsStore from '../stores/query'

export default function useServerTablePaginationParams() {
  const { page, per_page, order, order_by } = storeToRefs(useQueryParamsStore())

  return {
    page,
    per_page,
    order,
    order_by,
  }
}

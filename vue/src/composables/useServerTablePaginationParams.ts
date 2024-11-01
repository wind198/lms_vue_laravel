import useQueryParamsStore from '../stores/query'
import { IOrder } from '../types/common.type'
import {
  DEFAULT_ORDER,
  DEFAULT_ORDER_BY,
  DEFAULT_PAGE,
  DEFAULT_PER_PAGE,
} from '../utils/constants'

export default function useServerTablePaginationParams() {
  const { searchParams } = useQueryParamsStore()

  const page: number = searchParams.page || DEFAULT_PAGE
  const per_page: number = searchParams.per_page || DEFAULT_PER_PAGE
  const order: IOrder = searchParams.order || DEFAULT_ORDER
  const order_by: string = searchParams.order_by || DEFAULT_ORDER_BY

  return { page, per_page, order, order_by }
}

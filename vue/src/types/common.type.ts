export const OrderList = ['asc', 'desc'] as const
export type IOrder = (typeof OrderList)[number]
export type IPaginatedData<T> = {
  data: T[]
  params: {
    from: number
    to: number
    total: number
  }
}
export type IHasSize = { size: 'small' | 'medium' | 'large' }

export type IFilter<T> = {
  filterKey: string
  defaultValue?: T
  alwaysOn?: boolean
  label?: string
  isVisible: boolean
}

export type IQueryListParams = {
  page: number
  per_page: number
  order: IOrder
  order_by: string
  filter: string
}

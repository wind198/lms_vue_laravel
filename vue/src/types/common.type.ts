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

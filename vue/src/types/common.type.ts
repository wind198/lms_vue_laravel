export const OrderList = ['asc', 'desc'] as const
export type IOrder = (typeof OrderList)[number]

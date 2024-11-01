import { ISearchParamKey } from '../stores/query'
import { IOrder } from '../types/common.type'

export const TOKEN_KEY = 'TOKEN_KEY'
export const RF_TOKEN_KEY = 'RF_TOKEN_KEY'
export const X_XSRF_TOKEN = 'X-XSRF-TOKEN'
export const USER_KEY = 'USER_KEY'

export const WEB_API_URL: string = import.meta.env.VITE_API_URL || ''

export const DEFAULT_PAGE = 1
export const DEFAULT_PER_PAGE = 10
export const DEFAULT_ORDER_BY = 'created_at'
export const DEFAULT_ORDER = 'desc' as IOrder
export const DEFAULT_PER_PAGE_ITEMS = [10, 25, 50, 100]
export const PAGINATION_SEARCH_PARAMS = [
  'page',
  'per_page',
  'order_by',
  'order',
  'filter',
] as ISearchParamKey[]
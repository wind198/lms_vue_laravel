import { defineStore } from 'pinia'
import {
  DEFAULT_ORDER,
  DEFAULT_ORDER_BY,
  DEFAULT_PAGE,
  DEFAULT_PER_PAGE,
  PAGINATION_SEARCH_PARAMS,
} from '../utils/constants'
import { merge } from 'lodash-es'

export const DefaultSearchParams = {
  page: DEFAULT_PAGE,
  per_page: DEFAULT_PER_PAGE,
  order: DEFAULT_ORDER,
  order_by: DEFAULT_ORDER_BY,
  filter: {},
}
export type ISearchParamKey = keyof typeof DefaultSearchParams

export type ISearchParams = typeof DefaultSearchParams

const useQueryParamsStore = defineStore('query', {
  state(): { searchParams: ISearchParams } {
    return {
      searchParams: DefaultSearchParams,
    }
  },
  actions: {
    updateSearchParams(v: Partial<ISearchParams>) {
      this.searchParams = merge(this.searchParams, v)
    },
  },
})

export default useQueryParamsStore

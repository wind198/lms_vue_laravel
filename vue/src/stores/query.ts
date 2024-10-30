import { defineStore } from 'pinia'
import {
  DEFAULT_ORDER,
  DEFAULT_ORDER_BY,
  DEFAULT_PAGE,
  DEFAULT_PER_PAGE,
  PAGINATION_SEARCH_PARAMS,
} from '../utils/constants'
import { merge } from 'lodash-es'

type ISearchParamKey = (typeof PAGINATION_SEARCH_PARAMS)[number]

type ISearchParams = Record<ISearchParamKey, any>

const useQueryParamsStore = defineStore('query', {
  state(): { searchParams: ISearchParams } {
    return {
      searchParams: {
        page: DEFAULT_PAGE,
        per_page: DEFAULT_PER_PAGE,
        order: DEFAULT_ORDER,
        order_by: DEFAULT_ORDER_BY,
        filter: {},
      },
    }
  },
  actions: {
    updateSearchParams(v: Partial<ISearchParams>) {
      this.searchParams = merge(this.searchParams, v)
    },
  },
})

export default useQueryParamsStore

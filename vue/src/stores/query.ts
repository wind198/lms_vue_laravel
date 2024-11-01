import { defineStore } from 'pinia'
import {
  DEFAULT_ORDER,
  DEFAULT_ORDER_BY,
  DEFAULT_PAGE,
  DEFAULT_PER_PAGE,
  PAGINATION_SEARCH_PARAMS,
} from '../utils/constants'
import { merge } from 'lodash-es'
import { parse } from 'qs'

export const DefaultSearchParams = {
  page: DEFAULT_PAGE,
  per_page: DEFAULT_PER_PAGE,
  order: DEFAULT_ORDER,
  order_by: DEFAULT_ORDER_BY,
  filter: {} as Record<string, any>,
  augmented: true,
}
export type ISearchParamKey = keyof typeof DefaultSearchParams

export type ISearchParams = typeof DefaultSearchParams

export const SearchParamKeyList = Object.keys(DefaultSearchParams)

const useQueryParamsStore = defineStore('query', {
  state(): { searchParams: ISearchParams } {
    let searchStr = window.location.search
    if (!searchStr) {
      return {
        searchParams: DefaultSearchParams,
      }
    }

    searchStr = searchStr.replace(/^\?/, '')
    const searchParams = parse(searchStr)
    console.log({ searchParams })
    return {
      searchParams: merge(DefaultSearchParams, searchParams as any),
    }
  },
  actions: {
    updateSearchParams(v: Partial<ISearchParams>) {
      this.searchParams = merge(this.searchParams, v)
    },
  },
})

export default useQueryParamsStore

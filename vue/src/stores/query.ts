import { ref, reactive, computed } from 'vue'
import { defineStore } from 'pinia'
import {
  DEFAULT_ORDER,
  DEFAULT_ORDER_BY,
  DEFAULT_PAGE,
  DEFAULT_PER_PAGE,
} from '../utils/constants'
import { cloneDeep, isEmpty, merge, pick } from 'lodash-es'
import { parse } from 'qs'
import { removeNullFields } from '../utils/helpers'

// Define default search parameters
export const getDefaultSearchParams = () => ({
  page: DEFAULT_PAGE,
  per_page: DEFAULT_PER_PAGE,
  order: DEFAULT_ORDER,
  order_by: DEFAULT_ORDER_BY,
  filter: {} as Record<string, any>,
  augmented: true,
})

export type ISearchParams = ReturnType<typeof getDefaultSearchParams>
export type ISearchParamKey = keyof ISearchParams

export const SearchParamKeyList = Object.keys(getDefaultSearchParams())

export const PaginationParamKeyList = [
  'page',
  'per_page',
  'order',
  'order_by',
] as ISearchParamKey[]

export type IPaginationSearchParamKey = (typeof PaginationParamKeyList)[number]

// Define the store with Composition API
export const useQueryParamsStore = defineStore('query', () => {
  // Initialize state based on URL or default values

  const searchParams = reactive<ISearchParams>(getDefaultSearchParams())

  onMounted(() => {
    mapQueryStringToStore(window.location.search)
  })

  // Actions
  function updatePaginationParams(
    v: Partial<Pick<ISearchParams, IPaginationSearchParamKey>>
  ) {
    for (const key in pick(v, PaginationParamKeyList)) {
      const element = v[key]
      if (['string', 'boolean', 'number'].includes(typeof element))
        searchParams[key] = element
    }
  }

  function mapQueryStringToStore(queryString: string) {
    const searchStr = queryString
      .replace(/^\?/, '')
      .split('&')
      .map(decodeURI)
      .join('&')

    const initialParams = searchStr ? parse(decodeURIComponent(searchStr)) : {}

    const formatedInitialParams = {} as Partial<ISearchParams>

    for (const k in initialParams) {
      const v = initialParams[k]
      if (['page', 'per_page'].includes(k)) {
        formatedInitialParams[k] = typeof v === 'number' ? v : parseInt(v)
      } else if ('filter' === k) {
        const formatedValue = cloneDeep(v)
        // Add necessary format for filter fields
        formatedInitialParams.filter = formatedValue
      } else {
        formatedInitialParams[k] = v
      }
    }

    const paramsToUpdateStore = merge(
      getDefaultSearchParams(),
      formatedInitialParams
    )
    updateSearchParams(paramsToUpdateStore)
  }

  function updateFilterParams(p: Record<string, any>) {
    let newFilter = cloneDeep(searchParams.filter)
    newFilter = removeNullFields(merge(newFilter, p))

    searchParams.filter = newFilter
  }

  function updateSearchParams(p: Partial<ISearchParams>) {
    for (const key in p) {
      const element = p[key]

      if (key === 'filter' && !isEmpty(element)) {
        searchParams.filter = removeNullFields(
          merge(searchParams.filter, element)
        )
      } else if (
        key !== 'filter' &&
        ['string', 'number', 'boolean'].includes(typeof element)
      ) {
        searchParams[key] = element
      }
    }
  }

  function setAugmented(p: boolean) {
    searchParams.augmented = p
  }

  const filterParams = computed(() => searchParams.filter)

  const page = computed(() => searchParams.page)
  const per_page = computed(() => searchParams.per_page)
  const order = computed(() => searchParams.order)
  const order_by = computed(() => searchParams.order_by)
  const augmented = computed(() => searchParams.augmented)

  return {
    filterParams,
    updatePaginationParams,
    updateFilterParams,
    updateSearchParams,
    setAugmented,
    page,
    per_page,
    order,
    order_by,
    augmented,
    mapQueryStringToStore,
  }
})

export default useQueryParamsStore

import { isEmpty, merge, omit } from 'lodash-es'
import { parse, stringify } from 'qs'
import { NavigationGuardWithThis, RouteLocationRaw } from 'vue-router'
import useQueryParamsStore, {
  getDefaultSearchParams,
  ISearchParamKey,
  ISearchParams,
} from '../../stores/query'
import {
  extractQueryString,
  parseQueryStringToLocationQuery,
} from '../../utils/helpers'
import { storeToRefs } from 'pinia'

export const buildQueryMiddleware: NavigationGuardWithThis<undefined> = async (
  to,
  from
) => {
  const { path, hash, fullPath } = to

  const { augmented } = storeToRefs(useQueryParamsStore())

  const queryStore = useQueryParamsStore()

  const defaultSearchParams = getDefaultSearchParams()

  // If the URL is already checked, then just proceed without doing anything
  if (augmented.value) {
    return
  }

  let objectToUpdateSearchParams = parse(
    extractQueryString(fullPath)
  ) as Partial<ISearchParams>

  objectToUpdateSearchParams.augmented = true

  // If path has changed, then we need to reset the search params first, then apply the new one from the URL
  if (from.path !== to.path) {
    objectToUpdateSearchParams = merge(
      getDefaultSearchParams(),
      objectToUpdateSearchParams
    )
  }

  if (!isEmpty(objectToUpdateSearchParams)) {
    queryStore.updateSearchParams(objectToUpdateSearchParams)
  }

  const objectToBuildQueryStr = {} as Partial<ISearchParams>

  for (const key in omit(queryStore.searchParams, 'augumented')) {
    const k = key as ISearchParamKey

    const element =
      objectToUpdateSearchParams[key] ?? queryStore.searchParams[key]
    if (k === 'augmented') {
      continue
    }
    if (k === 'filter' && isEmpty(element)) {
      continue
    }
    if (
      k !== 'filter' &&
      !['string', 'number', 'boolean'].includes(typeof element)
    ) {
      continue
    }
    if (element === defaultSearchParams[key]) {
      continue
    }
    // @ts-expect-error
    objectToBuildQueryStr[key] = element
  }

  const queryStr = stringify(objectToBuildQueryStr)

  let output = `${path}`
  if (queryStr.length > 1) {
    if (!queryStr.startsWith('?')) {
      output += '?' + queryStr
    } else output += queryStr
  }
  if (hash.length > 1) {
    output += hash
  }

  queryStore.setAugmented(true)

  return {
    query: parseQueryStringToLocationQuery(queryStr),
    path,
    hash,
  } as RouteLocationRaw
}

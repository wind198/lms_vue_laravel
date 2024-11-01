import { isEmpty, pick, uniq } from 'lodash-es'
import { stringify } from 'qs'
import { NavigationGuardWithThis, RouteLocationRaw } from 'vue-router'
import useQueryParamsStore, {
  ISearchParamKey,
  ISearchParams,
} from '../../stores/query'

export const buildQueryMiddleware: NavigationGuardWithThis<undefined> = async (
  to,
  from
) => {
  const { path, query, hash, meta, matched, fullPath } = useRoute()

  const { searchParams, updateSearchParams } = useQueryParamsStore()

  if (searchParams.augmented) {
    return
  }

  let searchParamsForThisRoute: string[] = []
  matched.forEach((m) => {
    if (m.meta.searchParams?.length) {
      searchParamsForThisRoute.push(...(m.meta.searchParams as string[]))
    }
  })

  searchParamsForThisRoute = uniq(searchParamsForThisRoute)
  if (!searchParamsForThisRoute?.length) {
    updateSearchParams({ augmented: true })
    return
  }

  const objectToBuildQueryStr = {} as Partial<ISearchParams>

  for (const key in pick(searchParams, searchParamsForThisRoute)) {
    const k = key as ISearchParamKey

    // @ts-expect-error
    const element = searchParams[key]
    if (k === 'filter' && isEmpty(element)) {
      continue
    }
    if (!['string', 'number', 'boolean'].includes(typeof element)) {
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

  console.log(`${fullPath} -> ${output}`)
  updateSearchParams({ augmented: true })

  // @ts-expect-error
  return {
    query: objectToBuildQueryStr,
    path,
    hash,
    replace: true,
  } as RouteLocationRaw
}

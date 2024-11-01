import { pick } from 'lodash-es'
import { stringify } from 'qs'
import { NavigationGuardWithThis } from 'vue-router'
import useQueryParamsStore, { ISearchParams } from '../../stores/query'

export const buildQueryMiddleware: NavigationGuardWithThis<undefined> = async (
  to,
  from
) => {
  const { meta, path, query, hash, fullPath } = to

  const searchParamsForThisRoute: string[] =
    (meta.searchParams as unknown as string[]) ?? []

  if (!searchParamsForThisRoute?.length) {
    return
  }

  const { searchParams } = useQueryParamsStore()

  const objectToBuildQueryStr = {} as Partial<ISearchParams>

  for (const key in pick(searchParams, searchParamsForThisRoute)) {
    if (Object.prototype.hasOwnProperty.call(objectToBuildQueryStr, key)) {
      // @ts-expect-error
      const element = objectToBuildQueryStr[key]
      if (!['string', 'number', 'boolean'].includes(typeof element)) {
        continue
      }
      // @ts-expect-error
      objectToBuildQueryStr[key] = element
    }
  }

  const queryStr = stringify(objectToBuildQueryStr)

  const output = `${path}?${queryStr}${hash}`
  console.log(`${fullPath} -> ${output}`)
  return output
}

import { pick } from 'lodash-es'
import { stringify } from 'qs'
import { NavigationGuardWithThis } from 'vue-router'
import useQueryParamsStore from '../../stores/query'

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

  const queryStr = stringify(pick(searchParams, searchParamsForThisRoute))

  const output = `${path}?${queryStr}${hash}`
  console.log(`${fullPath} -> ${output}`)
  return output
}

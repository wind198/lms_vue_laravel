import type { UseFetchOptions } from '#app'
import { extractErrorMsgFromFetchErr } from '../utils/helpers'
import { useToast } from './useToast'

export const useApiFetch = <T>(url: string, options: UseFetchOptions<T>) => {
  const config = useRuntimeConfig()

  const { show } = useToast()

  const data = useFetch(`${config.public.apiUrl}/${url}`, options).catch(
    e => {
      const msg = extractErrorMsgFromFetchErr(e)
      show({ message: msg, type: 'error' })
      throw e
    }
  )

  return data
}

import { Options } from 'redaxios'
import { httpClient } from '../utils/httpClient'
import { useToast } from './useToast'
import { extractErrorMsgFromFetchErr } from '../utils/helpers'

type IMethod = 'get' | 'post' | 'patch' | 'delete'

const makeApiCall = <T>(method: IMethod) => httpClient[method]<T>

export default function useHttpClient() {
  const { show } = useToast()

  const handleHttpErr = (error: any) => {
    show({ message: extractErrorMsgFromFetchErr(error), type: 'error' })
    throw error
  }

  const $get = <T>(url: string, config?: Options) => {
    try {
      return makeApiCall<T>('get')(url, config)
    } catch (error) {
      return handleHttpErr(error)
    }
  }
  const $post = <T, U>(url: string, body: T, config?: Options) => {
    try {
      // @ts-expect-error
      return makeApiCall<U>('post')(url, body, config)
    } catch (error) {
      return handleHttpErr(error)
    }
  }
  const $patch = <T, U>(url: string, body: T, config?: Options) => {
    try {
      // @ts-expect-error
      return makeApiCall<U>('patch')(url, body, config)
    } catch (error) {
      return handleHttpErr(error)
    }
  }

  const $delete = <T>(url: string, config?: Options) => {
    try {
      return makeApiCall<T>('delete')(url, config)
    } catch (error) {
      return handleHttpErr(error)
    }
  }

  return { $get, $post, $patch, $delete }
}

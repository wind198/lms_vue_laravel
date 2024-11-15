import axios, { AxiosError, AxiosRequestConfig, AxiosResponse } from 'axios'
import { useToastStore } from '../stores/toast'
import { extractErrorMsgFromFetchErr } from '../utils/helpers'
import apiHttpClient from '../utils/httpClient'
import useAuthStore from '../stores/auth'

export default function useApiHttpClient() {
  const { show } = useToastStore()

  const { setAuthenticate } = useAuthStore()

  const router = useRouter()

  const handleHttpErr = (error: any) => {
    const statusCode = (error as AxiosError)?.status
    show({ message: extractErrorMsgFromFetchErr(error), type: 'error' })
    if (statusCode === 401) {
      setAuthenticate(false)
      router.push('/auth/login')
    }
    throw error
  }

  const $get = <T = any, R = AxiosResponse<T>, D = any>(
    url: string,
    config?: AxiosRequestConfig<D>
  ) => {
    try {
      return apiHttpClient.get<T, R, D>(url, config)
    } catch (error) {
      return handleHttpErr(error)
    }
  }
  const $post = <T = any, R = AxiosResponse<T>, D = any>(
    url: string,
    data: D,
    config?: AxiosRequestConfig<D>
  ) => {
    try {
      return apiHttpClient.post<T, R, D>(url, data, config)
    } catch (error) {
      return handleHttpErr(error)
    }
  }
  const $patch = <T = any, R = AxiosResponse<T>, D = any>(
    url: string,
    data?: D,
    config?: AxiosRequestConfig<D>
  ) => {
    try {
      return apiHttpClient.patch<T, R, D>(url, data, config)
    } catch (error) {
      return handleHttpErr(error)
    }
  }

  const $delete = <T = any, R = AxiosResponse<T>, D = any>(
    url: string,
    config?: AxiosRequestConfig<D>
  ) => {
    try {
      return apiHttpClient.delete<T, R, D>(url, config)
    } catch (error) {
      return handleHttpErr(error)
    }
  }

  return { $get, $post, $patch, $delete }
}

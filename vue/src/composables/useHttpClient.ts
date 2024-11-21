import axios, { AxiosError, AxiosRequestConfig, AxiosResponse } from 'axios'
import { useToastStore } from '../stores/toast'
import apiHttpClient from '../utils/httpClient'
import useAuthStore from '../stores/auth'

export default function useApiHttpClient() {
  const { show } = useToastStore()

  const { setAuthenticate } = useAuthStore()

  const router = useRouter()

  const handleHttpErr = (error: any) => {
    const statusCode = (error as AxiosError)?.status
    if (statusCode === 401) {
      setAuthenticate(false)
      router.push('/auth/login')
    }
    throw error
  }

  const $get = async <T = any, R = AxiosResponse<T>, D = any>(
    url: string,
    config?: AxiosRequestConfig<D>
  ) => {
    try {
      const data = await apiHttpClient.get<T, R, D>(url, config)
      return data
    } catch (error) {
      return handleHttpErr(error)
    }
  }
  const $post = async <T = any, R = AxiosResponse<T>, D = any>(
    url: string,
    data: D,
    config?: AxiosRequestConfig<D>
  ) => {
    try {
      const res = await apiHttpClient.post<T, R, D>(url, data, config)
      return res
    } catch (error) {
      return handleHttpErr(error)
    }
  }
  const $patch = async <T = any, R = AxiosResponse<T>, D = any>(
    url: string,
    data?: D,
    config?: AxiosRequestConfig<D>
  ) => {
    try {
      const res = await apiHttpClient.patch<T, R, D>(url, data, config)
      return res
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

import { AxiosHeaders } from 'axios'
import { IOrder } from '../types/common.type'
import dayjs, { ConfigType } from 'dayjs'

/**
 * HELPERS
 */
export const extractErrorMsgFromFetchErr = (e: any) => {
  console.error(e)
  return 'Some err'
}

export const apiPrefix = (i: string) => {
  const input = i.replace(/^\//, '')
  if (input.startsWith('api')) {
    return input
  }
  return ['api', input].join('/')
}

export const reserveOrder = (i: IOrder) => (i === 'asc' ? 'desc' : 'asc')

export const joinStr = (...i: string[]) => i.join(' ')

export const getAge = (i: ConfigType) => dayjs().diff(i, 'year')

import { AxiosHeaders } from 'axios'
import { IOrder } from '../types/common.type'

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

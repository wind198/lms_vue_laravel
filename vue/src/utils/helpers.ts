import { AxiosHeaders } from 'axios'

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

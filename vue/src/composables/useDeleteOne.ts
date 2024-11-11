import useApiHttpClient from '@/composables/useHttpClient'
import { IStringOrNumber } from '@/types/common.type'
import { apiPrefix, getOneUrl } from '@/utils/helpers'
import { useMutation, useQuery } from '@tanstack/vue-query'
import { AxiosError } from 'axios'

type IOptions = {
  id: IStringOrNumber
  resource: string
  resourcePlular?: string
}

export default function useDeleteOne<T>(options: IOptions) {
  const { id, resource, resourcePlular = resource + 's' } = options

  const { $delete } = useApiHttpClient()

  const deleteOne = async () => {
    if (!id) {
      throw new Error(`Invalid id: ${id}`)
    }
    const { data } = await $delete(getOneUrl(resourcePlular, id))
    return data
  }

  const res = useMutation<T, AxiosError, T>({
    mutationFn: deleteOne,
  })

  return res
}

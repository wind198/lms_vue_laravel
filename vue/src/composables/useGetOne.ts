import useApiHttpClient from '@/composables/useHttpClient'
import { apiPrefix, getOneUrl } from '@/utils/helpers'
import { useQuery } from '@tanstack/vue-query'
import { AxiosError } from 'axios'

type IOptions = {
  id: Ref<number | undefined>
  resource: string
  resourcePlular?: string
}

export default function useGetOne<T>(options: IOptions) {
  const { id, resource, resourcePlular = resource + 's' } = options

  const { $get } = useApiHttpClient()

  const getOne = async () => {
    if (!id.value) {
      throw new Error(`Invalid id: ${id.value}`)
    }
    const { data } = await $get(getOneUrl(resourcePlular, id.value))
    return data
  }

  const res = useQuery<T, AxiosError, T>({
    queryKey: [resourcePlular, 'getOne', { id }],
    queryFn: getOne,
  })

  return res
}

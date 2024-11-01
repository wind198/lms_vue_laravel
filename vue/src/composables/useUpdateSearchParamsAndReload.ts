import useQueryParamsStore, { ISearchParams } from '../stores/query'

export default function useUpdateSearchParamsAndReload() {
  const router = useRouter()

  const { path } = useRoute()

  const { updateSearchParams } = useQueryParamsStore()

  const updateSearchParamsAndReload = (payload: Partial<ISearchParams>) => {
    updateSearchParams(payload)
    router.push({
      path,
      query: {
        t: new Date().toISOString(),
      },
    })
  }

  return { updateSearchParamsAndReload }
}

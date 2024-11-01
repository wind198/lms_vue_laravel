import useQueryParamsStore, { ISearchParams } from '../stores/query'

export default function useUpdateSearchParamsAndNavigate() {
  const router = useRouter()

  const { path } = useRoute()

  const { updateSearchParams } = useQueryParamsStore()

  const $push = (payload: Partial<ISearchParams>) => {
    updateSearchParams({ ...payload, augmented: false })
    router.push({
      path,
      force: true,
    })
  }

  return { $push }
}

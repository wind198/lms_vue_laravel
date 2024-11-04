import useQueryParamsStore, { DefaultSearchParams } from '../stores/query'

export default function useServerTableEventHandler() {
  const { setAugmented, updatePaginationParams } = useQueryParamsStore()

  const router = useRouter()

  const handleUpdateSort = (v: any) => {
    setAugmented(false)
    if (!v.length) {
      updatePaginationParams({
        order: DefaultSearchParams.order,
        order_by: DefaultSearchParams.order_by,
      })
    } else {
      const { key: orderBy, order } = v[0]

      updatePaginationParams({
        order,
        order_by: orderBy,
      })
    }
    router.push({ force: true })
  }

  const handleUpdatePage = (v: number) => {
    setAugmented(false)
    updatePaginationParams({ page: v })
    router.push({ force: true })
  }
  const handleUpdatePerPage = (v: number) => {
    setAugmented(false)
    updatePaginationParams({ per_page: v })
    router.push({ force: true })
  }

  return { handleUpdateSort, handleUpdatePage, handleUpdatePerPage }
}

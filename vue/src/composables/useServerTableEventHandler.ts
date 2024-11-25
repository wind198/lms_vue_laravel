import useQueryParamsStore from '../stores/query'
import { DEFAULT_ORDER, DEFAULT_ORDER_BY } from '../utils/constants'

export default function useServerTableEventHandler() {
  const { setAugmented, updatePaginationParams } = useQueryParamsStore()

  const { path } = useRoute()

  const router = useRouter()

  const handleUpdateSort = (v: any) => {
    if (!v.length) {
      updatePaginationParams({
        order: DEFAULT_ORDER,
        order_by: DEFAULT_ORDER_BY,
      })
      setAugmented(false)
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
    updatePaginationParams({ page: v })
    setAugmented(false)

    router.push({ force: true })
  }
  const handleUpdatePerPage = (v: number) => {
    setAugmented(false)
    updatePaginationParams({ per_page: v })
    setAugmented(false)

    router.push({ force: true })
  }

  return { handleUpdateSort, handleUpdatePage, handleUpdatePerPage }
}

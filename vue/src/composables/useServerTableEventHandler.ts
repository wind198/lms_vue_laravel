import useQueryParamsStore from '../stores/query'
import { DEFAULT_ORDER, DEFAULT_ORDER_BY } from '../utils/constants'

export default function useServerTableEventHandler() {
  const { setAugmented, updatePaginationParams } = useQueryParamsStore()

  const { path } = useRoute()

  const router = useRouter()

  const handleUpdateSort = (v: any) => {
    setAugmented(false)
    if (!v.length) {
      updatePaginationParams({
        order: DEFAULT_ORDER,
        order_by: DEFAULT_ORDER_BY,
      })
    } else {
      const { key: orderBy, order } = v[0]

      updatePaginationParams({
        order,
        order_by: orderBy,
      })
    }
    router.push({ force: true, path })
  }

  const handleUpdatePage = (v: number) => {
    setAugmented(false)
    updatePaginationParams({ page: v })
    router.push({ force: true, path })
  }
  const handleUpdatePerPage = (v: number) => {
    setAugmented(false)
    updatePaginationParams({ per_page: v })
    router.push({ force: true, path })
  }

  return { handleUpdateSort, handleUpdatePage, handleUpdatePerPage }
}

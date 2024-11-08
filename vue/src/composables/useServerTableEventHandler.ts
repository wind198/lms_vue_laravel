import useQueryParamsStore from '../stores/query'
import { DEFAULT_ORDER, DEFAULT_ORDER_BY } from '../utils/constants'

export default function useServerTableEventHandler() {
  const { setAugmented, updatePaginationParams } = useQueryParamsStore()

  const { path } = useRoute()

  const router = useRouter()

  const handleUpdateSort = (v: any) => {
    if (!v.length) {
      updatePaginationParams({
        augmented: false,
        order: DEFAULT_ORDER,
        order_by: DEFAULT_ORDER_BY,
      })
    } else {
      const { key: orderBy, order } = v[0]

      updatePaginationParams({
        augmented: false,
        order,
        order_by: orderBy,
      })
    }
    router.push({ force: true })
  }

  const handleUpdatePage = (v: number) => {
    updatePaginationParams({ page: v, augmented: false })
    router.push({ force: true })
  }
  const handleUpdatePerPage = (v: number) => {
    setAugmented(false)
    updatePaginationParams({ per_page: v, augmented: false })
    router.push({ force: true })
  }

  return { handleUpdateSort, handleUpdatePage, handleUpdatePerPage }
}

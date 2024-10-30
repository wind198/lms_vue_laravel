import { Ref } from 'vue'
import { IOrder } from '../types/common.type'
import { DEFAULT_ORDER, DEFAULT_ORDER_BY } from '../utils/constants'
import useQueryParamsStore from '../stores/query'

// type IProps<T> = {
//     data: T[];
//     params: IServerTableParams<T>;
//     headers: any[];
// };

export default function useServerTable<T>() {
  const { searchParams, updateSearchParams } = useQueryParamsStore()

  const router = useRouter()

  const { path } = useRoute()

  const handleChangeOrder = (key?: string, newOrder?: IOrder) => {
    key = key || DEFAULT_ORDER_BY
    newOrder = newOrder || DEFAULT_ORDER
    updateSearchParams({ order: key, order_by: newOrder })
    router.push(path)
  }

  const handleChangePage = (v: number) => {
    updateSearchParams({ page: v })
    router.push(path)
  }
  const handleChangePerPage = (v: number) => {
    const urlParams = new URLSearchParams(window.location.search)
    if (v === DefaultTablePerPage) {
      urlParams.delete('per_page')
    } else {
      urlParams.set('per_page', v.toString())
    }

    const data = Object.fromEntries(urlParams.entries())

    router.get(window.location.pathname, data, { preserveState: true })
  }

  const handleChangeFilter = (newFilters: Record<string, any>) => {
    const urlParams = new URLSearchParams(window.location.search)

    let paramsObject = Object.fromEntries(urlParams.entries())

    paramsObject = removeValueFromObject(paramsObject, (_, k) =>
      k.startsWith('filter')
    )

    const data = {
      ...paramsObject,
      filters: newFilters,
    }

    let newUrl = window.location.pathname

    const queryStr = stringify(data)

    newUrl += `?${queryStr}`

    router.visit(newUrl, { preserveState: true })
  }

  return {
    handleChangeFilter,
    handleChangePage,
    handleChangeOrder,
    handleChangePerPage,
  }
}

import { debounce, get, set } from 'lodash-es'
import useQueryParamsStore from '../stores/query'
import { storeToRefs } from 'pinia'
import { defineExpose } from 'vue'

type IOptions<T> = {
  defaultValue?: T
  filterKey: string
  debounceTime?: number
}

export default function useFilterFeatures<T>(options: IOptions<T>) {
  const { filterKey, defaultValue, debounceTime } = options

  const filterPaths = computed(() => filterKey.split('.'))

  const { updateFilterParams, setAugmented } = useQueryParamsStore()

  const { filterParams } = storeToRefs(useQueryParamsStore())

  const currentSearchParamValue = computed(() =>
    get(filterParams.value, filterPaths.value)
  )

  const router = useRouter()

  const _onChangeValue = (v: any) => {
    const payload = {}
    set(payload, filterPaths.value, v)
    updateFilterParams(payload)
    setAugmented(false)
    router.push({ force: true })
  }

  const onChangeValue = debounceTime
    ? debounce(_onChangeValue, debounceTime)
    : _onChangeValue

  onMounted(() => {
    if (defaultValue === undefined) {
      return
    }
    if (defaultValue === currentSearchParamValue.value) {
      return
    }
    onChangeValue(defaultValue)
  })

  return { onChangeValue, currentSearchParamValue }
}

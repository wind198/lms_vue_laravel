import { debounce, get, set } from 'lodash-es'
import useQueryParamsStore, { ISearchParams } from '../stores/query'
import { storeToRefs } from 'pinia'
import { defineExpose } from 'vue'

type IOptions<T> = {
  defaultValue?: T
  filterKey: string
  debounceTime?: number
  isVisible: Ref<boolean>
}

export default function useFilterFeatures<T>(options: IOptions<T>) {
  const { filterKey, defaultValue, debounceTime, isVisible } = options

  const filterPaths = computed(() => filterKey.split('.'))

  const { updateFilterParams } = useQueryParamsStore()

  const { filterParams } = storeToRefs(useQueryParamsStore())

  const currentSearchParamValue = computed(() =>
    get(filterParams.value, filterPaths.value)
  )

  const router = useRouter()

  const _onChangeValue = (v: any) => {
    const payload = { augmented: false } as Partial<ISearchParams>
    set(payload, filterPaths.value, v)
    updateFilterParams(payload)
    router.push({ force: true })
  }

  const onChangeValue = debounceTime
    ? debounce(_onChangeValue, debounceTime)
    : _onChangeValue

  watch(
    isVisible,
    (v) => {
      if (!v) {
        onChangeValue(null)
        return
      }
      if (defaultValue === undefined) {
        return
      }
      onChangeValue(defaultValue)
    },
    { immediate: true }
  )

  return { onChangeValue, currentSearchParamValue }
}

import { removeTrailingSlash } from '@/utils/helpers'

export default function useIsEditPage() {
  const { path } = useRoute()

  const isEdit = computed(() => removeTrailingSlash(path).endsWith('update'))

  return { isEdit }
}

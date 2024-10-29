import { uniqueId } from 'lodash-es'
import { ref } from 'vue'

export type IToastOptions = {
  id: string
  message: string
  title?: string
  type?: 'success' | 'error' | 'info' | 'warning'
  duration?: number
}

export const useToast = () => {
  const toasts = ref<IToastOptions[]>([])

  const show = (options: Omit<IToastOptions, 'id'>) => {
    const id = uniqueId()
    toasts.value.push({ ...options, id })

    // Auto-remove toast after specified duration
    setTimeout(() => {
      toasts.value = toasts.value.filter((i) => i.id !== id)
    }, options.duration || 3000)
  }

  return { show, toasts }
}

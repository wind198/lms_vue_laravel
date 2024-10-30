import { uniqueId } from 'lodash-es'
import { defineStore } from 'pinia'

export type IToastOptions = {
  id: string
  message: string
  title?: string
  type?: 'success' | 'error' | 'info' | 'warning'
  duration?: number
}

export const useToastStore = defineStore('toasts', {
  state() {
    return { toasts: [] as IToastOptions[] }
  },
  actions: {
    show(options: Omit<IToastOptions, 'id'>) {
      const id = uniqueId()
      this.toasts.push({ ...options, id })

      // Auto-remove toast after specified duration
      setTimeout(() => {
        this.toasts = this.toasts.filter((i) => i.id !== id)
      }, options.duration || 3000)
    },
  },
})

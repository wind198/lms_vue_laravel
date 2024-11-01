import { defineStore } from 'pinia'

type ICommonStoreState = {
  longTextDialog: {
    isOpen: boolean
    text: string
    title?: string
  }
}

export const useCommonStuffStore = defineStore('common', {
  state: (): ICommonStoreState => ({
    longTextDialog: {
      isOpen: false,
      text: '',
      title: '',
    },
  }),
  actions: {
    openLongTextDialog(payload: { text: string; title?: string }) {
      this.longTextDialog = {
        isOpen: true,
        text: payload.text,
        title: payload.title,
      }
    },
    closeLongTextDialog(v: string) {
      this.longTextDialog = {
        isOpen: false,
        text: '',
        title: '',
      }
    },
  },
})

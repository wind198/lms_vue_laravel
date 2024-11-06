import { defineStore } from 'pinia'
import { ref, reactive } from 'vue'

export const useCommonStuffStore = defineStore('common', () => {
  const longTextDialog = reactive<{
    isOpen: boolean
    text: string
    title?: string
  }>({
    isOpen: false,
    text: '',
    title: '',
  })

  const deleteBulkDialog = reactive<{
    isOpen: boolean
    title: string
    text: string
    deleteApi: string
    ids: number[]
    queryKey: any
  }>({
    isOpen: false,
    title: '',
    text: '',
    deleteApi: '',
    ids: [],
    queryKey: undefined,
  })

  function openLongTextDialog(payload: { text: string; title?: string }) {
    longTextDialog.isOpen = true
    longTextDialog.text = payload.text
    longTextDialog.title = payload.title
  }

  function closeLongTextDialog() {
    longTextDialog.isOpen = false
    longTextDialog.text = ''
    longTextDialog.title = ''
  }

  function openDeleteBulkDialog(payload: {
    text: string
    title: string
    deleteApi: string
    queryKey: any
    ids: number[]
  }) {
    deleteBulkDialog.isOpen = true
    deleteBulkDialog.text = payload.text
    deleteBulkDialog.title = payload.title
    deleteBulkDialog.deleteApi = payload.deleteApi
    deleteBulkDialog.ids = payload.ids
    deleteBulkDialog.queryKey = payload.queryKey
  }

  function closeDeleteBulkDialog() {
    deleteBulkDialog.isOpen = false
    deleteBulkDialog.text = ''
    deleteBulkDialog.title = ''
    deleteBulkDialog.deleteApi = ''
    deleteBulkDialog.ids = []
    deleteBulkDialog.queryKey = undefined
  }

  return {
    longTextDialog,
    openLongTextDialog,
    closeLongTextDialog,
    deleteBulkDialog,
    openDeleteBulkDialog,
    closeDeleteBulkDialog,
  }
})

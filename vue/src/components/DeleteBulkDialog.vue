<script setup lang="ts">
import useApiHttpClient from '@/composables/useHttpClient'
import { useCommonStuffStore } from '@/stores/common'
import { useToastStore } from '@/stores/toast'
import { useQueryClient } from '@tanstack/vue-query'
import { useI18n } from 'vue-i18n'

const commonStore = useCommonStuffStore()

const { t } = useI18n()

const isOpen = computed(() => commonStore.deleteBulkDialog.isOpen)
const text = computed(() => commonStore.deleteBulkDialog.text)
const title = computed(() => commonStore.deleteBulkDialog.title)
const ids = computed(() => commonStore.deleteBulkDialog.ids)
const deleteApi = computed(() => commonStore.deleteBulkDialog.deleteApi)
const queryKey = computed(() => commonStore.deleteBulkDialog.queryKey)

const { $delete } = useApiHttpClient()

const { show } = useToastStore()

const queryClient = useQueryClient()

const onConfirm = async () => {
  try {
    await $delete(deleteApi.value, { params: { ids: ids.value } })
    show({ message: t('messages.info.deletedSuccessfully'), type: 'success' })
  } catch (error) {
    console.error(error)
  } finally {
    commonStore.closeDeleteBulkDialog()
    queryClient.invalidateQueries(queryKey.value)
  }
}

const onClose = () => {
  commonStore.closeDeleteBulkDialog()
}
</script>
<template>
  <v-dialog max-width="500" :model-value="isOpen">
    <v-card :title="title">
      <v-card-text>
        {{ text }}
      </v-card-text>

      <v-card-actions>
        <v-spacer />
        <v-btn variant="flat" :text="t('actions.close')" @click="onClose" />
        <v-btn
          variant="flat"
          color="primary"
          :text="t('actions.confirm')"
          @click="onConfirm"
        />
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>
<style scoped></style>

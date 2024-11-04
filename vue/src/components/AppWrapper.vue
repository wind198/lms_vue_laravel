<script setup lang="ts">
import { storeToRefs } from 'pinia'
import { IToastOptions, useToastStore } from '../stores/toast.js'
import { useI18n } from 'vue-i18n'
import { VueQueryDevtools } from '@tanstack/vue-query-devtools'
import useQueryParamsStore from '../stores/query.js'

const { toasts } = storeToRefs(useToastStore())

const { t } = useI18n()

const getDefaultToastAlertTitle = (type: IToastOptions['type']) => {
  switch (type) {
    case 'error':
    case 'warning':
    case 'success':
      return t('messages.info.' + type)
    default:
      return undefined
  }
}

const { mapQueryStringToStore } = useQueryParamsStore()

const onPopCallback = () => {
  mapQueryStringToStore(window.location.search)
}

onMounted(() => {
  mapQueryStringToStore(window.location.search)
  window.onpopstate = onPopCallback
})
</script>
<template>
  <div class="app-wrapper">
    <slot />
    <div class="toast-list d-flex flex-column">
      <v-alert
        v-for="to in toasts"
        :key="to.id"
        class="mb-2"
        :text="to.message"
        :title="to.title || getDefaultToastAlertTitle(to.type)"
        :type="to.type"
        variant="flat"
      />
    </div>
    <long-text-dialog />
    <VueQueryDevtools />
  </div>
</template>
<style scoped>
.toast-list {
  position: fixed;
  bottom: 24px;
  left: 50%;
  transform: translateX(-50%);
  z-index: 2500;
}
</style>

<script setup lang="ts">
import { storeToRefs } from 'pinia'
import { IToastOptions, useToastStore } from '../stores/toast.js'
import { useI18n } from 'vue-i18n'

const { toasts } = storeToRefs(useToastStore())

const { t } = useI18n()

const getDefaultTitle = (type: IToastOptions['type']) => {
  switch (type) {
    case 'error':
    case 'warning':
    case 'success':
      return t('messages.info.' + type)
    default:
      return undefined
  }
}
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
        :title="to.title || getDefaultTitle(to.type)"
        :type="to.type"
        variant="flat"
      />
    </div>
    <long-text-dialog />
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

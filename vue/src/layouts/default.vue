<script setup lang="ts">
import { IToastOptions, useToastStore } from '../stores/toast.js'

const { toasts } = useToastStore()

const getDefaultTitle = (type: IToastOptions['type']) => {
  switch (type) {
    case 'error':
      return 'Error'
    case 'warning':
      return 'warning'
    case 'success':
      return 'success'
    default:
      return 'info'
  }
}
</script>
<template>
  <app-wrapper>
    <router-view />
    <div class="toast-list d-flex flex-column">
      <v-alert
        v-for="to in toasts"
        :key="to.id"
        class="mb-2"
        :text="to.message"
        :title="to.title || getDefaultTitle(to.type)"
        :type="to.type"
      />
    </div>
  </app-wrapper>
</template>
<style scoped>
.toast-list {
  position: fixed;
  bottom: 24px;
  left: 50%;
  transform: translateX(-50%);
}
</style>

<script setup lang="ts">
  import type { IToastOptions } from '../composables/useToast.js'

  const { toasts } = useToast()

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
  <div class="nuxt-app">
    <slot />
    <div class="toast-list d-flex flex-column">
      <v-alert
        v-for="to in toasts"
        class="mb-2"
        :text="to.message"
        :title="to.title || getDefaultTitle(to.type)"
        :type="to.type"
      />
    </div>
  </div>
</template>
<style scoped>
.toast-list {
  position: fixed;
  bottom: 24px;
  left: 50%;
  transform: translateX(-50%);
}
</style>

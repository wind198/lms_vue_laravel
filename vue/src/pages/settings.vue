<script setup lang="ts">
definePage({
  meta: {
    layout: 'home',
    label: 'nouns.management',
  },
  redirect: '/settings/students',
})
</script>
<template>
  <div class="setting-page py-3 pl-2 pr-4">
    <AppBreadcrumbs />

    <RouterView />
  </div>
</template>
<style scoped></style>

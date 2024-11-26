<script setup lang="ts">
definePage({
  meta: {
    label: 'nouns.course',
    title: ['nouns.list', { entity: 'nouns.course' }],
    isBreadcrumb: true,
  },
})
</script>

<template>
  <div class="courses-page-wrapper">
    <AppBreadcrumbs />
    <RouterView />
  </div>
</template>
<style scoped></style>

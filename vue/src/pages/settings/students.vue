<script setup lang="ts">
definePage({
  meta: {
    label: 'nouns.student',
    title: ['nouns.list', { entity: 'nouns.student' }],
    isBreadcrumb: true,
  },
})
</script>

<template>
  <div class="students-page-wrapper">
    <AppBreadcrumbs />
    <RouterView />
  </div>
</template>
<style scoped></style>

<script setup lang="ts">
definePage({
  meta: {
    label: 'nouns.major',
    title: ['nouns.list', { entity: 'nouns.major' }],
    isBreadcrumb: true,
  },
})
</script>

<template>
  <div class="majors-page-wrapper">
    <AppBreadcrumbs />
    <RouterView />
  </div>
</template>
<style scoped></style>

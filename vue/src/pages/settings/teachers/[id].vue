<script setup lang="ts">
definePage({
  meta: {
    isBreadcrumb: true,
    isIdRoute: true,
    title: ['actions.view', { entity: 'nouns.teacher' }],
  },
})
</script>
<template>
  <div class="teacher-page-wrapper">
    <RouterView />
  </div>
</template>
<style scoped></style>

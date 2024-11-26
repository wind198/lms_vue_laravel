<script setup lang="ts">
definePage({
  meta: {
    isBreadcrumb: true,
    isIdRoute: true,
    title: ['actions.view', { entity: 'nouns.course' }],
  },
})
</script>
<template>
  <div class="course-page-wrapper">
    <RouterView />
  </div>
</template>
<style scoped></style>

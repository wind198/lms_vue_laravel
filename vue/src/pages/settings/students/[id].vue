<script setup lang="ts">
definePage({
  meta: {
    isBreadcrumb: true,
    isIdRoute: true,
    title: ['actions.view', { entity: 'nouns.student' }],
  },
})
</script>
<template>
  <div class="student-page-wrapper">
    <RouterView />
  </div>
</template>
<style scoped></style>

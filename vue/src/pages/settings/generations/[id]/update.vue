<script setup lang="ts">
definePage({
  meta: {
    label: 'actions.update',
    title: ['actions.update', { entity: 'nouns.student' }],
    isBreadcrumb: true,
  },
})
</script>
<template>
  <div class="update-student-page">
    <CreateGeneration />
  </div>
</template>
<style scoped></style>

<script setup lang="ts">
definePage({
  meta: {
    label: 'actions.update',
    title: ['actions.update', { entity: 'nouns.course' }],
    isBreadcrumb: true,
  },
})
</script>
<template>
  <div class="update-course-page">
    <CreateCourse />
  </div>
</template>
<style scoped></style>

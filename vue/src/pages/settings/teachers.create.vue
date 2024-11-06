<script setup lang="ts">
definePage({
  meta: {
    label: ['actions.create', { entity: 'nouns.teacher' }],
  },
})
</script>
<template>
  <div class="create-teacher-page">
    <AppBreadcrumbs />
    <CreateUser user-type="teacher" />
  </div>
</template>
<style scoped></style>

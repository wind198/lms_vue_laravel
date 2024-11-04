<script setup lang="ts">
definePage({
  meta: {
    label: ['actions.create', { entity: 'nouns.student' }],
  },
})
</script>
<template>
  <div class="create-student-page">
    <AppBreadcrumbs />
    <CreateUser user-type="student" />
  </div>
</template>
<style scoped></style>

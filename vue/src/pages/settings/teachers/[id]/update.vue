<script setup lang="ts">
definePage({
  meta: {
    label: 'actions.update',
    title: ['actions.update', { entity: 'nouns.teacher' }],
    isBreadcrumb: true,
  },
})
</script>
<template>
  <div class="update-teacher-page">
    <CreateUser user-type="teacher" />
  </div>
</template>
<style scoped></style>

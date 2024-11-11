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
    <CreateUser user-type="student" />
  </div>
</template>
<style scoped></style>

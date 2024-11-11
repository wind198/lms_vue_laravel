<script setup lang="ts">
definePage({
  meta: {
    label: 'actions.create',
    title: ['actions.create', { entity: 'nouns.student' }],
    isBreadcrumb: true,
  },
})
</script>
<template>
  <div class="create-student-page">
    <CreateUser user-type="student" />
  </div>
</template>
<style scoped></style>

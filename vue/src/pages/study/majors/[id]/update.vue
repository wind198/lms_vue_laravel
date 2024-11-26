<script setup lang="ts">

definePage({
  meta: {
    label: 'actions.update',
    title: ['actions.update', { entity: 'nouns.major' }],
    isBreadcrumb: true,
  },
})
</script>
<template>
  <div class="update-major-page">
    <CreateMajor />
  </div>
</template>
<style scoped></style>

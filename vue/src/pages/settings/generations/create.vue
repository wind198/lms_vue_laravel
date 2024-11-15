<script setup lang="ts">
import CreateGeneration from '@/components/CreateGeneration.vue'

definePage({
  meta: {
    label: 'actions.create',
    title: ['actions.create', { entity: 'nouns.generation' }],
    isBreadcrumb: true,
  },
})
</script>

<template>
  <div class="create-teacher-page">
    <CreateGeneration />
  </div>
</template>
<style scoped></style>

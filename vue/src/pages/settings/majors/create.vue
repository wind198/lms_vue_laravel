<script setup lang="ts">
import CreateMajor from '@/components/CreateMajor.vue'

definePage({
  meta: {
    label: 'actions.create',
    title: ['actions.create', { entity: 'nouns.major' }],
    isBreadcrumb: true,
  },
})
</script>

<template>
  <div class="create-major-page">
    <CreateMajor />
  </div>
</template>
<style scoped></style>

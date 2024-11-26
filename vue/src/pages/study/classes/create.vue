<script setup lang="ts">
import CreateCourse from '@/components/CreateCourse.vue'

definePage({
  meta: {
    label: 'actions.create',
    title: ['actions.create', { entity: 'nouns.class' }],
    isBreadcrumb: true,
  },
})
</script>

<template>
  <div class="create-class-page">
    <CreateCourse />
  </div>
</template>
<style scoped></style>

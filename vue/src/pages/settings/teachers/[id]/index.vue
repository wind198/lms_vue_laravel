<script setup lang="ts">
definePage({
  meta: {
    isBreadcrumb: true,
    label: 'nouns.detail',
    title: ['others.viewDetail', { entity: 'nouns.teacher' }],
  },
})
</script>
<template>
  <div class="show-teacher-page">
    <ShowUser user_type="teacher" />
  </div>
</template>
<style scoped></style>

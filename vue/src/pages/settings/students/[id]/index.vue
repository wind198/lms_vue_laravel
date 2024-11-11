<script setup lang="ts">
definePage({
  meta: {
    isBreadcrumb: true,
    label: 'nouns.detail',
    title: ['others.viewDetail', { entity: 'nouns.student' }],
  },
})
</script>
<template>
  <div class="view-user-detail">
    <ShowUser user_type="student" />
  </div>
</template>
<style scoped></style>

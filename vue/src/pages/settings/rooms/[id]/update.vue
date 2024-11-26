<script setup lang="ts">
definePage({
  meta: {
    label: 'actions.update',
    title: ['actions.update', { entity: 'nouns.room' }],
    isBreadcrumb: true,
  },
})
</script>
<template>
  <div class="update-room-page">
    <CreateRoom />
  </div>
</template>
<style scoped></style>

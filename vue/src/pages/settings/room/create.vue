<script setup lang="ts">
import CreateRoom from '@/components/CreateRoom.vue'

definePage({
  meta: {
    label: 'actions.create',
    title: ['actions.create', { entity: 'nouns.room' }],
    isBreadcrumb: true,
  },
})
</script>

<template>
  <div class="create-room-page">
    <CreateRoom />
  </div>
</template>
<style scoped></style>

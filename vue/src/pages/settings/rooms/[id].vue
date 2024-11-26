<script setup lang="ts">
definePage({
  meta: {
    isBreadcrumb: true,
    isIdRoute: true,
    title: ['actions.view', { entity: 'nouns.room' }],
  },
})
</script>
<template>
  <div class="room-page-wrapper">
    <RouterView />
  </div>
</template>
<style scoped></style>

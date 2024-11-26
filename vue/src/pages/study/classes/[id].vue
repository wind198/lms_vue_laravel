<script setup lang="ts">
definePage({
  meta: {
    isBreadcrumb: true,
    isIdRoute: true,
    title: ['actions.view', { entity: 'nouns.class' }],
  },
})
</script>
<template>
  <div class="class-page-wrapper">
    <RouterView />
  </div>
</template>
<style scoped></style>

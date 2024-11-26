<script setup lang="ts">
definePage({
  meta: {
    isBreadcrumb: true,
    isIdRoute: true,
    title: ['actions.view', { entity: 'nouns.major' }],
  },
})
</script>
<template>
  <div class="major-page-wrapper">
    <RouterView />
  </div>
</template>
<style scoped></style>

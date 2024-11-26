<script setup lang="ts">
definePage({
  meta: {
    layout: 'home',
    label: 'nouns.study',
  },
  redirect: '/study/majors',
})
</script>
<template>
  <div class="setting-page py-3 pl-2 pr-4">
    <RouterView />
  </div>
</template>
<style scoped></style>

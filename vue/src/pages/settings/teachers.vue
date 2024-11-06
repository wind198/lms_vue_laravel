<script setup lang="ts">
import ListUser from '@/components/ListUser.vue'

definePage({
  meta: {
    label: 'nouns.teacher',
    title: ['nouns.list', { entity: 'nouns.teacher' }],
  },
})
</script>

<template>
  <div class="list-teachers">
    <AppBreadcrumbs />
    <ListUser user_type="teacher" />
  </div>
</template>
<style scoped></style>

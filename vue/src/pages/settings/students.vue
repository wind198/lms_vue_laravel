<script setup lang="ts">
import ListUser from '@/components/ListUser.vue'

definePage({
  meta: {
    label: 'nouns.student',
    title: ['nouns.list', { entity: 'nouns.student' }],
  },
})
</script>

<template>
  <div class="list-students">
    <AppBreadcrumbs />
    <ListUser user_type="student" />
  </div>
</template>
<style scoped></style>

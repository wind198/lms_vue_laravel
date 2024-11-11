<script setup lang="ts">
import useDeleteOne from '@/composables/useDeleteOne.js'
import { IHasId, IStringOrNumber } from '@/types/common.type.js'

const props = defineProps<{
  representation: string
  editUrl?: string
  resource: string
  id: IStringOrNumber
}>()

const { mutateAsync, isPending } = useDeleteOne({
  id: props.id,
  resource: props.resource,
})
</script>
<template>
  <div class="d-flex align-items">
    <VBtn
      color="primary"
      variant="text"
      density="compact"
      icon
      v-if="editUrl"
      :to="editUrl"
      class="mr-1"
    >
      <VIcon size="small" icon="mdi-pencil" />
    </VBtn>
    <VBtn
      color="error"
      :loading="isPending"
      @click="mutateAsync"
      variant="text"
      density="compact"
      icon
    >
      <VIcon size="small" icon="mdi-delete" />
    </VBtn>
  </div>
</template>
<style scoped></style>

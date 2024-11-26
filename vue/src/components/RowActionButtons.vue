<script setup lang="ts">
import useDeleteOne from '@/composables/useDeleteOne.js'
import { IHasId, IHasResource, IStringOrNumber } from '@/types/common.type.js'

const props = defineProps<
  {
    editUrl?: string
    id: IStringOrNumber
  } & IHasResource
>()

const { mutateAsync, isPending } = useDeleteOne({
  id: props.id,
  resource: props.resource,
  resourcePlular: props.resourcePlural,
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

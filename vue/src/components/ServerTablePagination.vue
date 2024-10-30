<script setup lang="ts">
import { computed } from 'vue'
import { VPagination, VSelect } from 'vuetify/components'
import { DEFAULT_PER_PAGE_ITEMS } from '../utils/constants.js'

type IProps = {
  perPage: number
  page: number
  totalItems: number
  length: number
}

const emit = defineEmits(['updatePerPage', 'updatePage'])

const props = defineProps<IProps>()

const paginationText = computed(() => {
  const startItemIndex = (props.page - 1) * props.perPage + 1
  const totalItems = props.totalItems
  const endItemIndex = Math.min(totalItems, props.page * props.perPage)
  return `${startItemIndex} to ${endItemIndex} of ${totalItems}`
})
</script>
<template>
  <div class="server-table-pagination d-flex justify-end align-center">
    <span class="mb-0 text-body-2 align-self-center mr-4">{{ paginationText }}
    </span>
    <VSelect
      :model-value="props.perPage"
      class="flex-grow-0 item-per-page-select"
      :items="DEFAULT_PER_PAGE_ITEMS"
      label="Per page"
      @update:model-value="emit('updatePerPage', $event)"
    />
    <VPagination
      :model-value="props.page"
      :length="props.length"
      :total-visible="5"
      @update:model-value="emit('updatePage', $event)"
    />
  </div>
</template>
<style scoped>
.item-per-page-select {
  width: 100px;
}
</style>

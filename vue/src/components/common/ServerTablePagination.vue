<script setup lang="ts">
import { computed } from 'vue'
import { VPagination, VSelect } from 'vuetify/components'
import { DEFAULT_PER_PAGE_ITEMS } from '../../utils/constants.js'

type IProps = {
  perPage: number
  page: number
  totalItems: number
}

const emit = defineEmits(['updatePerPage', 'updatePage'])

const props = defineProps<IProps>()

const totalPages = computed(() =>
  Math.max(1, Math.ceil(props.totalItems / props.perPage))
)
const paginationText = computed(() => {
  const startItemIndex = (props.page - 1) * props.perPage + 1
  const totalItems = props.totalItems
  const endItemIndex = Math.min(totalItems, props.page * props.perPage)
  return `${startItemIndex} to ${endItemIndex} of ${totalItems}`
})

const pagination = useTemplateRef('pagination')

// onMounted(() => {
//   const nav = pagination.value?.$el
//   if (!nav) {
//     return
//   }
//   const activeBtn = (nav as HTMLElement).querySelectorAll('.v-pagination__item > button')[
//     props.page - 1
//   ] as HTMLButtonElement
//   if (!activeBtn) {
//     return
//   }
//   activeBtn?.click()
// })
</script>
<template>
  <div class="server-table-pagination d-flex justify-end align-center">
    <span class="mb-0 text-body-2 align-self-center mr-4"
      >{{ paginationText }}
    </span>
    <VSelect
      :model-value="perPage"
      class="flex-grow-0 item-per-page-select"
      :items="DEFAULT_PER_PAGE_ITEMS"
      label="Per page"
      @update:model-value="emit('updatePerPage', $event)"
    />
    <VPagination
      ref="pagination"
      :model-value="page"
      :length="totalPages"
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

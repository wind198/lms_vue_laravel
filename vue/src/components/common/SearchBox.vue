<script setup lang="ts">
import { debounce } from 'lodash-es'
import useUpdateSearchParamsAndNavigate from '../../composables/useUpdateSearchParamsAndNavigate.js'
import { IHasSize } from '../../types/common.type.js'

const props = defineProps<
  {
    value: string
  } & Partial<IHasSize>
>()

const width = computed(() => {
  switch (props.size) {
    case 'small':
      return 160
    case 'large':
      return 400
    default:
      return 240
  }
})

const { $push } = useUpdateSearchParamsAndNavigate()

const updateSearchParamQ = debounce((v: string) => {
  $push({
    filter: {
      q: v,
    },
  })
}, 400)
</script>
<template>
  <v-text-field
    hide-details="auto"
    class="search-box"
    :width="width + 'px'"
    :model-value="value"
    prepend-inner-icon="mdi-magnify"
    @update:model-value="updateSearchParamQ($event)"
    density="comfortable"
  />
</template>
<style scoped>
.search-box {
  flex-grow: 0;
}
</style>

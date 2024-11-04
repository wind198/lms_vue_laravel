<script setup lang="ts">
import useFilterFeatures from '@/composables/useFilterFeatures.js'
import { IFilter, IHasSize } from '../../types/common.type.js'

const props = defineProps<IFilter<string> & Partial<IHasSize>>()

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
const { onChangeValue, currentSearchParamValue } = useFilterFeatures({
  defaultValue: props.defaultValue,
  filterKey: props.filterKey,
  debounceTime: 400,
})
// define expose not work in composable, otherwise it should have been defined in useFilterFeatures
defineExpose({
  filterKey: props.filterKey,
  label: props.label,
  alwaysOn: !!props.alwaysOn,
  defaultValue: !!props.defaultValue,
})
</script>
<template>
  <v-text-field
    clearable
    hide-details="auto"
    class="search-box"
    :width="width + 'px'"
    :model-value="currentSearchParamValue"
    prepend-inner-icon="mdi-magnify"
    @update:model-value="onChangeValue($event)"
    @click:clear="onChangeValue(null)"
    density="comfortable"
  />
</template>
<style scoped>
.search-box {
  flex-grow: 0;
}
</style>

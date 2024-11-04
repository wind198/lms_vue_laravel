<script setup lang="ts">
import useFilterFeatures from '@/composables/useFilterFeatures.js'
import dayjs, { ConfigType } from 'dayjs'
import useFormatDateTime from '../../composables/useFormatDateTime.js'
import { IFilter, IHasSize } from '../../types/common.type.js'

const props = defineProps<
  IFilter<ConfigType> & {
    showTime?: boolean
  } & Partial<IHasSize>
>()

const { formatDateCommon, formatDateTimeCommon } = useFormatDateTime()

const { onChangeValue, currentSearchParamValue } = useFilterFeatures({
  defaultValue: props.defaultValue,
  filterKey: props.filterKey,
})

const modelValue = computed(() => {
  return currentSearchParamValue.value
    ? dayjs(currentSearchParamValue.value)
    : null
})

const displayText = computed(() => {
  if (!currentSearchParamValue.value) {
    return ''
  }
  if (props.showTime) {
    return formatDateTimeCommon(currentSearchParamValue.value)
  }
  return formatDateCommon(currentSearchParamValue.value)
})

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

const handleUpdateModelValue = (v: any) => {
  if (!v) {
    onChangeValue(null)
    return
  }
  onChangeValue(dayjs(v).toISOString())
}

const onClickClearFilter = () => {
  onChangeValue(null)
}

defineExpose({
  filterKey: props.filterKey,
  label: props.label,
  alwaysOn: !!props.alwaysOn,
  defaultValue: !!props.defaultValue,
})
</script>

<template>
  <div class="date-filter">
    <v-menu>
      <template #activator="{ props }">
        <v-text-field
          :model-value="displayText"
          :label="label"
          :style="{ flexGrow: 0, width: width + 'px' }"
          v-bind="props"
          hide-details="auto"
          clearable
          @click:clear="onClickClearFilter"
        />
      </template>
      <v-date-picker
        hide-header
        elevation="8"
        :model-value="modelValue"
        @update:model-value="handleUpdateModelValue"
      />
    </v-menu>
  </div>
</template>
<style scoped></style>

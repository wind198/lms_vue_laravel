<script setup lang="ts">
import useFilterFeatures from '@/composables/useFilterFeatures'
import { IFilter, IHasSize } from '@/types/common.type'
import { GENDER_LIST, IGender } from '@/utils/constants'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

const props = defineProps<
  IFilter<IGender> & {
    showTime?: boolean
  } & Partial<IHasSize>
>()

const isVisible = computed(() => props.isVisible)

const items = GENDER_LIST.map((i) => ({
  title: t('nouns.' + i.toLowerCase()),
  value: i,
}))

const { onChangeValue, currentSearchParamValue } = useFilterFeatures({
  defaultValue: props.defaultValue,
  filterKey: props.filterKey,
  isVisible,
})

const width = computed(() => {
  switch (props.size) {
    case 'small':
      return 140
    case 'large':
      return 320
    default:
      return 200
  }
})

defineExpose({
  filterKey: props.filterKey,
  label: props.label,
  alwaysOn: !!props.alwaysOn,
  defaultValue: !!props.defaultValue,
})
</script>
<template>
  <VSelect
    :label="label"
    v-if="isVisible"
    clearable
    hide-details="auto"
    :model-value="currentSearchParamValue"
    @update:model-value="onChangeValue($event)"
    @click:clear="onChangeValue(null)"
    :items="items"
    :style="{ flexGrow: 0, width: width + 'px' }"
  />
</template>
<style scoped></style>

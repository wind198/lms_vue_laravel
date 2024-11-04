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

const items = GENDER_LIST.map((i) => ({
  title: t('nouns.' + i.toLowerCase()),
  value: i,
}))

const { onChangeValue, currentSearchParamValue } = useFilterFeatures({
  defaultValue: props.defaultValue,
  filterKey: props.filterKey,
})

const width = computed(() => {
  switch (props.size) {
    case 'small':
      return 100
    case 'large':
      return 200
    default:
      return 120
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

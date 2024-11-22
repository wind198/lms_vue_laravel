<script setup lang="ts">
import useFormatDateTime from '@/composables/useFormatDateTime.js'
import dayjs, { ConfigType } from 'dayjs'
import {
  VDatePicker,
  VDatePickerMonth,
  VDatePickerYears,
} from 'vuetify/components'

const props = defineProps<{
  isYearPicker?: boolean
  isMonthPicker?: boolean
  label: string
}>()

defineOptions({
  inheritAttrs: false,
})

const modelValue = defineModel<ConfigType | number>()

const { formatDateCommon, getFriendlyMonthName } = useFormatDateTime()

const displayText = computed(() => {
  if (props.isYearPicker) {
    return modelValue.value
  }
  if (props.isMonthPicker) {
    return getFriendlyMonthName(modelValue.value as number)
  }
  return formatDateCommon(modelValue.value)
})

const handleUpdateDateValue = ($event: any) => {
  if (props.isMonthPicker || props.isYearPicker) {
    modelValue.value = $event
  } else {
    modelValue.value = dayjs($event).toDate()
  }
}

const pickerClasses = ['bg-white']
</script>
<template>
  <div class="date-picker-with-popup-menu">
    <v-menu>
      <template #activator="{ props }">
        <v-text-field
          :model-value="displayText"
          :label="label"
          v-bind="{ ...props, ...$attrs }"
          hide-details="auto"
          clearable
          @click:clear="modelValue = undefined"
        />
      </template>
      <VDatePickerYears
        :class="pickerClasses"
        v-if="isYearPicker"
        hide-header
        elevation="8"
        :model-value="modelValue as any"
        @update:model-value="handleUpdateDateValue"
      />
      <VDatePickerMonth
        v-else-if="isMonthPicker"
        :class="pickerClasses"
        hide-header
        elevation="8"
        :model-value="modelValue as any"
        @update:model-value="handleUpdateDateValue"
      />
      <VDatePicker
        v-else
        :class="pickerClasses"
        hide-header
        elevation="8"
        :model-value="modelValue"
        @update:model-value="handleUpdateDateValue"
      />
    </v-menu>
  </div>
</template>
<style scoped></style>

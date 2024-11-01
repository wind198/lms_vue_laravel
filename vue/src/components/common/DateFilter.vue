<script setup lang="ts">
import { ConfigType } from 'dayjs'
import useFormatDateTime from '../../composables/useFormatDateTime.js'
import { IHasSize } from '../../types/common.type.js'

const props = defineProps<
  {
    value: ConfigType
    label: string
    filterKey: string
    showTime?: boolean
  } & Partial<IHasSize>
>()

const { formatDateCommon, formatDateTimeCommon } = useFormatDateTime()

const displayText = computed(() => {
  if (props.showTime) {
    return formatDateTimeCommon(props.value)
  }
  return formatDateCommon(props.value)
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
</script>

<template>
  <div class="text-center">
    <v-menu>
      <template #activator="{ props }">
        <v-text-field
          :model-value="displayText"
          :label="label"
          :style="{ flexGrow: 0, width: width + 'px' }"
          v-bind="props"
          hide-details="auto"
        />
      </template>
      Hello
    </v-menu>
  </div>
</template>
<style scoped></style>

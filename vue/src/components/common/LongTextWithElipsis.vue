<script setup lang="ts">
import { useCommonStuffStore } from '../../stores/common.js'

const props = defineProps<{
  limit?: number
  text: string
  title?: string
}>()

const { openLongTextDialog } = useCommonStuffStore()

const handleShowAll = () => {
  openLongTextDialog({
    text: props.text,
    title: props.title,
  })
}

const textToRender = computed(() => props.text.slice(0, props.limit ?? 50))
</script>
<template>
  <div class="long-text">
    {{ textToRender }}
    <v-btn
      class="elipsis-btn"
      :elevation="0"
      density="compact"
      @click="handleShowAll"
      >...</v-btn
    >
  </div>
</template>
<style scoped>
.elipsis-btn {
  padding-left: 4px;
  padding-right: 4px;
  min-width: unset;
}
</style>

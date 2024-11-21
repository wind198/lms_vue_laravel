<script setup lang="ts">
const props = defineProps<{
  tabs: { isError?: boolean; value: string; label: string; to?: string }[]
  defaultTab: string
}>()

const currentTabValue = ref(props.defaultTab)
</script>
<template>
  <VTabs v-model="currentTabValue" class="mb-2">
    <VTab
      :class="{ 'text-error': t.isError }"
      :to="t.to"
      v-for="t in tabs"
      :value="t.value"
      :key="t.value"
      >{{ t.label }}</VTab>
  </VTabs>
  <v-tabs-window v-model="currentTabValue">
    <v-tabs-window-item v-for="t in tabs" :value="t.value" :key="t.value">
      <slot :name="t.value"> This is slot content for {{ t.value }} tab </slot>
    </v-tabs-window-item>
  </v-tabs-window>
</template>

<style scoped></style>

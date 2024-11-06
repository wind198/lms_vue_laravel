<script setup lang="ts">
import { useI18n } from 'vue-i18n'

defineProps<{
  selected: number
  total: number
  selectedText: string
}>()

const { t } = useI18n()

const emit = defineEmits(['clear'])
</script>
<template>
  <p class="pl-1 text-subtitle-2">
    <span v-if="!selected"> {{ t('nouns.total') }}: {{ total }} </span>
    <span v-else>
      {{ t('nouns.total') }}: {{ total }} /
      {{ selectedText }}
      <v-btn
        icon
        density="compact"
        variant="flat"
        size="small"
        @click="emit('clear')"
        class="remove-selection-btn"
      >
        <v-icon>mdi-close</v-icon>
      </v-btn>
    </span>
  </p>
</template>
<style scoped>
.remove-selection-btn {
  vertical-align: text-bottom;
  position: relative;
  transform: translateY(1px);
}
</style>

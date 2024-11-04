<script setup lang="ts">
import { useI18n } from 'vue-i18n'

const { t } = useI18n()
</script>
<template>
  <div class="bulk-actions-btn d-flex justify-end align-center pa-2">
    <v-btn append-icon="mdi-delete" color="error" variant="flat">
      {{ t('actions.delete') }}
    </v-btn>
  </div>
</template>
<style scoped></style>

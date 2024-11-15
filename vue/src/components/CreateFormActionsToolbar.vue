<script setup lang="ts">
import { useI18n } from 'vue-i18n'
const props = defineProps<{ isEdit: boolean }>()

const { t } = useI18n()
</script>
<template>
  <div class="d-flex w-full">
    <v-spacer />
    <v-btn variant="flat" type="reset">
      {{ t('actions.reset') }}
    </v-btn>
    <v-btn type="submit" color="primary" variant="flat">
      {{ t('actions.' + (isEdit ? 'save' : 'create')) }}
    </v-btn>
  </div>
</template>
<style scoped></style>

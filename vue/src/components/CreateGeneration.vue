<script setup lang="ts">
import useGenerationInputs from '@/composables/useGenerationInputs'
import useIsEditPage from '@/composables/useIsEditPage'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

const { onSubmit, onReset, descriptionField, titleField, yearField } =
  useGenerationInputs()

const { isEdit } = useIsEditPage()
</script>

<template>
  <VForm
    @submit.prevent="onSubmit"
    @reset.prevent="onReset"
    class="create-room-form"
  >
    <VSheet class="pa-3">
      <v-text-field
        :label="t('nouns.title')"
        :error-messages="titleField.errorMessage.value"
        v-model="titleField.value.value"
      />
      <v-textarea
        prepend-icon="mdi-file-document-outline"
        :label="t('nouns.description')"
        :error-messages="descriptionField.errorMessage.value"
        v-model="descriptionField.value.value"
      />
      <v-textarea
        prepend-icon="mdi-map-marker"
        :label="t('nouns.address')"
        :error-messages="descriptionField.errorMessage.value"
        v-model="descriptionField.value.value"
      />
      <CreateFormActionsToolbar :is-edit="isEdit" />
    </VSheet>
  </VForm>
</template>
<style scoped>
.create-generation-form {
  max-width: 800px;
  margin: auto;
}
</style>

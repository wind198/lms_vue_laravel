<script setup lang="ts">
import useMajorInputs from '@/composables/useMajorInputs'
import { removeTrailingSlash } from '@/utils/helpers.js'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

const { path, params } = useRoute()

const isEdit = computed(() => removeTrailingSlash(path).endsWith('update'))

const { onSubmit, onReset, descriptionField, titleField } = useMajorInputs()
</script>

<template>
  <VForm
    @submit.prevent="onSubmit"
    @reset.prevent="onReset"
    class="create-major-form"
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

      <CreateFormActionsToolbar :is-edit="isEdit" />
    </VSheet>
  </VForm>
</template>
<style scoped>
.create-major-form {
  max-width: 800px;
  margin: auto;
}
</style>

<script setup lang="ts">
import useGetOne from '@/composables/useGetOne.js'
import useRoomInputs from '@/composables/useRoomInputs'
import { removeTrailingSlash } from '@/utils/helpers.js'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

const { path, params } = useRoute()

// @ts-expect-error
const recordId = ref<number | undefined>(params.id)

const isEdit = computed(() => removeTrailingSlash(path).endsWith('update'))

const { data: userData } = useGetOne({
  id: recordId,
  resource: 'room',
  placeholderData: history.state.recordData,
})

const {
  useFormRes,
  onSubmit,
  onReset,
  descriptionField,
  titleField,
  addressField,
} = useRoomInputs()

watchEffect(() => {
  if (userData.value && !useFormRes.meta.value.dirty) {
    useFormRes.setValues(userData.value)
  }
})
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
        :error-messages="addressField.errorMessage.value"
        v-model="addressField.value.value"
      />

      <CreateFormActionsToolbar :is-edit="isEdit" />
    </VSheet>
  </VForm>
</template>
<style scoped>
.create-room-form {
  max-width: 800px;
  margin: auto;
}
</style>

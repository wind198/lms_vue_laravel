<script setup lang="ts">
import useGenerationInputs from '@/composables/useGenerationInputs'
import useGetOne from '@/composables/useGetOne.js'
import { IGenerationCoreField } from '@/types/entities/generation.entity'
import { IS_DEV } from '@/utils/constants'
import { removeTrailingSlash } from '@/utils/helpers.js'
import { faker } from '@faker-js/faker'
import dayjs from 'dayjs'
import { useI18n } from 'vue-i18n'

const getRandomGeneration = (): IGenerationCoreField => {
  return {
    title: faker.word.words(5),
    description: faker.word.words(20),
    year: faker.number.int({ min: 1990, max: 2024 }),
  }
}

const { t } = useI18n()

const initialValues = computed(() =>
  IS_DEV ? getRandomGeneration() : undefined
)

const { path, params } = useRoute()

const recordId = ref<number | undefined>(params['id'])

const isEdit = computed(() => removeTrailingSlash(path).endsWith('update'))

const { data: userData } = useGetOne({
  id: recordId,
  resource: 'generation',
  placeholderData: history.state.userData,
})

const {
  useFormRes,
  handleReset,
  onSubmit,
  descriptionField,
  titleField,
  yearField,
} = useGenerationInputs(initialValues)

watchEffect(() => {
  if (userData.value && !useFormRes.meta.value.dirty) {
    useFormRes.setValues(userData.value)
  }
})
</script>

<template>
  <VForm @submit.prevent="onSubmit" @reset.prevent="handleReset">
    <VSheet class="pa-3">
      <v-text-field
        :label="t('nouns.title')"
        :error-messages="titleField.errorMessage.value"
        v-model="titleField.value.value"
      />
      <v-textarea
        :label="t('nouns.description')"
        :error-messages="descriptionField.errorMessage.value"
        v-model="descriptionField.value.value"
      />
      <DatePickerWithMenuPopup
        :style="{ width: '400px' }"
        is-year-picker
        :label="t('nouns.year')"
        :error-messages="yearField.errorMessage.value"
        v-model="yearField.value.value"
      />
      <CreateFormActionsToolbar :is-edit="isEdit" />
    </VSheet>
  </VForm>
</template>
<style scoped></style>

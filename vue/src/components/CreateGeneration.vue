<script setup lang="ts">
import useGenerationInputs from '@/composables/useGenerationInputs'
import useApiHttpClient from '@/composables/useHttpClient'
import { IGenerationCoreField } from '@/types/entities/generation.entity'
import {
  IS_DEV,
  MAX_DESCRIPTION_LENGTH,
  MAX_TITLE_LENGTH,
} from '@/utils/constants'
import { faker } from '@faker-js/faker'
import { useForm } from 'vee-validate'
import { useI18n } from 'vue-i18n'
import { number, object, string } from 'yup'

const getRandomGeneration = (): IGenerationCoreField => {
  return {
    title: faker.word.words(5),
    description: faker.word.words(50),
    year: faker.number.int({ min: 1990, max: 2024 }),
  }
}

const { t } = useI18n()

const initialValues = computed(() =>
  IS_DEV ? getRandomGeneration() : undefined
)

const { handleReset, onSubmit, descriptionField, titleField, yearField } =
  useGenerationInputs(initialValues)
</script>

<template>
  <VForm @submit.prevent="onSubmit" @reset.prevent="handleReset">
    <VSheet class="pa-3">
      <v-text-field
        :label="t('nouns.title')"
        :error-messages="titleField.errorMessage.value"
        v-model="titleField.value.value"
      />
      <v-text-field
        :label="t('nouns.description')"
        :error-messages="descriptionField.errorMessage.value"
        v-model="descriptionField.value.value"
      />
      <VDatePickerYears
        :label="t('nouns.startYear')"
        v-model="yearField.value.value"
        :error-messages="yearField.errorMessage.value"
      />
    </VSheet>
  </VForm>
</template>
<style scoped></style>

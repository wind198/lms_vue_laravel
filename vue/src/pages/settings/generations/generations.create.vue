<script setup lang="ts">
import useGenerationInputs from '@/composables/useGenerationInputs'
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

definePage({
  meta: {
    label: ['actions.create', { entity: 'nouns.generation' }],
  },
})

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

const { handleReset, handleSubmit, descriptionField, titleField, yearField } =
  useGenerationInputs(initialValues)
</script>

<template>
  <div class="create-teacher-page">
    <AppBreadcrumbs />
    <CreateUser user-type="teacher" />
  </div>
</template>
<style scoped></style>

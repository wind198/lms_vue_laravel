import { useI18n } from 'vue-i18n'
import {
  IGeneration,
  IGenerationCoreField,
} from '@/types/entities/generation.entity'
import { date, number, object, string } from 'yup'
import { useField, useForm } from 'vee-validate'
import { Dayjs } from 'dayjs'
import {
  IS_DEV,
  MAX_DESCRIPTION_LENGTH,
  MAX_TITLE_LENGTH,
} from '@/utils/constants'
import useApiHttpClient from '@/composables/useHttpClient'
import { useToastStore } from '@/stores/toast'
import useQueryParamsStore from '@/stores/query'
import { apiPrefix, getOneUrl } from '@/utils/helpers'
import useIsEditPage from '@/composables/useIsEditPage'
import { faker } from '@faker-js/faker'
import useGetOne from '@/composables/useGetOne'

export type IGenerationForm = IGenerationCoreField

export type IUpdateGenerationForm = Partial<IGenerationForm>

const getRandomGeneration = (): IGenerationCoreField => {
  return {
    title: faker.word.words(5),
    description: faker.word.words(20),
    year: faker.number.int({ min: 1990, max: 2024 }),
  }
}

export default function useGenerationInputs() {
  const { t } = useI18n()

  const { isEdit } = useIsEditPage()

  const { params } = useRoute()

  // @ts-expect-error
  const recordId = params.id

  const stringRequiredSchema = string().required(
    t('messages.validations.required')
  )

  const validationSchema = object({
    title: stringRequiredSchema.max(
      MAX_TITLE_LENGTH,
      t('messages.validations.maxLength', { max: MAX_TITLE_LENGTH })
    ),
    description: string().max(
      MAX_DESCRIPTION_LENGTH,
      t('messages.validations.maxLength', { max: MAX_DESCRIPTION_LENGTH })
    ),
    year: number().min(1900, t('messages.validations.minValue', { min: 1900 })),
  })

  const initialValues = computed(() => {
    const state = history.state
    return isEdit
      ? state.recordData
      : IS_DEV
      ? getRandomGeneration()
      : undefined
  })

  const { data: generationData } = useGetOne<IGeneration>({
    id: recordId,
    resource: 'generation',
  })

  // Form and fields
  const useFormRes = useForm<IGenerationForm>({
    validationSchema,
    initialValues: initialValues?.value,
  })

  watchEffect(() => {
    if (generationData.value && !useFormRes.meta.value.dirty) {
      useFormRes.setValues(generationData.value)
    }
  })

  const titleField = useField<string>('title')
  const descriptionField = useField<string>('description')
  const yearField = useField<number>('year')

  const { $patch, $post } = useApiHttpClient()

  const { show } = useToastStore()

  const router = useRouter()

  const { setAugmented } = useQueryParamsStore()

  const onSubmit = useFormRes.handleSubmit(
    async (payload: IGenerationCoreField) => {
      try {
        if (isEdit) {
          // Update generation logic
          await $patch(getOneUrl('generations', recordId), payload)
          show({
            message: t('messages.info.savedSuccessfully'),
            type: 'success',
          })
        } else {
          await $post(apiPrefix('generations'), payload)
          show({
            message: t('messages.info.createdSuccessfully'),
            type: 'success',
          })

          // Create generation logic
        }
        setAugmented(false)
        router.push({
          path: '/settings/generations',
        })
      } catch (error) {
        console.error(error)
      }
    }
  )

  const onReset = () => {
    if (isEdit) {
      if (generationData.value) {
        useFormRes.setValues(generationData.value)
      }
      return
    }
    router.back()
  }

  return {
    useFormRes,
    titleField,
    descriptionField,
    yearField,
    onSubmit,
    onReset,
  }
}

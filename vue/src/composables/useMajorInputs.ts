import useGetOne from '@/composables/useGetOne'
import useApiHttpClient from '@/composables/useHttpClient'
import useIsEditPage from '@/composables/useIsEditPage'
import useQueryParamsStore from '@/stores/query'
import { useToastStore } from '@/stores/toast'
import { IMajor, IMajorCoreField } from '@/types/entities/major.entity'
import {
  IS_DEV,
  MAX_DESCRIPTION_LENGTH,
  MAX_TITLE_LENGTH,
} from '@/utils/constants'
import { apiPrefix, getOneUrl } from '@/utils/helpers'
import { faker } from '@faker-js/faker'
import { useField, useForm } from 'vee-validate'
import { useI18n } from 'vue-i18n'
import { object, string } from 'yup'

export type IMajorForm = IMajorCoreField

export type IUpdateMajorForm = Partial<IMajorForm>

const getRandomMajor = (): IMajorCoreField => {
  return {
    title: faker.word.words(5),
    description: faker.word.words(20),
  }
}

export default function useMajorInputs() {
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
  })

  const initialValues = computed(() => {
    const state = history.state
    return isEdit ? state.recordData : IS_DEV ? getRandomMajor() : undefined
  })

  const { data: majorData } = useGetOne<IMajor>({
    id: recordId,
    resource: 'majors',
  })

  watchEffect(() => {
    if (majorData.value && !useFormRes.meta.value.dirty) {
      useFormRes.setValues(majorData.value)
    }
  })

  // Form and fields
  const useFormRes = useForm<IMajorForm>({
    validationSchema,
    initialValues: initialValues?.value,
  })
  const titleField = useField<string>('title')
  const descriptionField = useField<string>('description')

  const { $patch, $post } = useApiHttpClient()

  const { show } = useToastStore()

  const router = useRouter()

  const { setAugmented } = useQueryParamsStore()

  const onSubmit = useFormRes.handleSubmit(async (payload: IMajorCoreField) => {
    try {
      if (isEdit) {
        // Update major logic
        await $patch(getOneUrl('majors', recordId), payload)
        show({
          message: t('messages.info.savedSuccessfully'),
          type: 'success',
        })
      } else {
        await $post(apiPrefix('majors'), payload)
        show({
          message: t('messages.info.createdSuccessfully'),
          type: 'success',
        })

        // Create major logic
      }
      setAugmented(false)
      router.push({
        path: '/settings/majors',
      })
    } catch (error) {
      console.error(error)
    }
  })

  const onReset = () => {
    if (isEdit) {
      if (majorData.value) {
        useFormRes.setValues(majorData.value)
      }
      return
    }
    router.back()
  }

  return {
    useFormRes,
    titleField,
    descriptionField,
    onSubmit,
    onReset,
  }
}

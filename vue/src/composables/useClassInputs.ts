import { useI18n } from 'vue-i18n'
import { date, number, object, string } from 'yup'
import { useField, useForm } from 'vee-validate'
import { Dayjs } from 'dayjs'
import {
  IS_DEV,
  MAX_ADDRESS_LENGTH,
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
import { IClass, IClassCoreField } from '@/types/entities/class.entity'

export type IClassForm = IClassCoreField

export type IUpdateClassForm = Partial<IClassForm>

const getRandomClassInputs = (): Partial<IClassCoreField> => {
  return {
    title: faker.word.words(5),
    description: faker.word.words(20),
  }
}

export default function useClassInputs() {
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
    course_id: number().required(t('messages.validations.required')),
  })

  const initialValues = computed(() => {
    const state = history.state
    return isEdit
      ? state.recordData
      : IS_DEV
      ? getRandomClassInputs()
      : undefined
  })

  const { data: classData } = useGetOne<IClass>({
    id: recordId,
    resource: 'class',
    resourcePlular: 'classes',

  })

  watchEffect(() => {
    if (classData.value && !useFormRes.meta.value.dirty) {
      useFormRes.setValues(classData.value)
    }
  })

  // Form and fields
  const useFormRes = useForm<IClassForm>({
    validationSchema,
    initialValues: initialValues?.value,
  })
  const titleField = useField<string>('title')
  const descriptionField = useField<string>('description')
  const courseField = useField<string>('course_id')

  const { $patch, $post } = useApiHttpClient()

  const { show } = useToastStore()

  const router = useRouter()

  const { setAugmented } = useQueryParamsStore()

  const onSubmit = useFormRes.handleSubmit(async (payload: IClassCoreField) => {
    try {
      if (isEdit) {
        // Update class logic
        await $patch(getOneUrl('classs', recordId), payload)
        show({
          message: t('messages.info.savedSuccessfully'),
          type: 'success',
        })
      } else {
        await $post(apiPrefix('classs'), payload)
        show({
          message: t('messages.info.createdSuccessfully'),
          type: 'success',
        })

        // Create class logic
      }
      setAugmented(false)
      router.push({
        path: '/settings/classs',
      })
    } catch (error) {
      console.error(error)
    }
  })

  const onReset = () => {
    if (isEdit) {
      if (classData.value) {
        useFormRes.setValues(classData.value)
      }
      return
    }
    router.back()
  }

  return {
    useFormRes,
    titleField,
    descriptionField,
    courseField,
    onSubmit,
    onReset,
  }
}

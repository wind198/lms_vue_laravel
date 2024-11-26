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
import { ICourse, ICourseCoreField } from '@/types/entities/course.entity'

export type ICourseForm = ICourseCoreField

export type IUpdateCourseForm = Partial<ICourseForm>

const getRandomCourseInputs = (): Partial<ICourseCoreField> => {
  return {
    title: faker.word.words(5),
    description: faker.word.words(20),
  }
}

export default function useCourseInputs() {
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
    major_id: number().required(t('messages.validations.required')),
  })

  const initialValues = computed(() => {
    const state = history.state
    return isEdit
      ? state.recordData
      : IS_DEV
      ? getRandomCourseInputs()
      : undefined
  })

  const { data: courseData } = useGetOne<ICourse>({
    id: recordId,
    resource: 'course',
  })

  watchEffect(() => {
    if (courseData.value && !useFormRes.meta.value.dirty) {
      useFormRes.setValues(courseData.value)
    }
  })

  // Form and fields
  const useFormRes = useForm<ICourseForm>({
    validationSchema,
    initialValues: initialValues?.value,
  })
  const titleField = useField<string>('title')
  const descriptionField = useField<string>('description')
  const majorField = useField<string>('major_id')

  const { $patch, $post } = useApiHttpClient()

  const { show } = useToastStore()

  const router = useRouter()

  const { setAugmented } = useQueryParamsStore()

  const onSubmit = useFormRes.handleSubmit(
    async (payload: ICourseCoreField) => {
      try {
        if (isEdit) {
          // Update course logic
          await $patch(getOneUrl('courses', recordId), payload)
          show({
            message: t('messages.info.savedSuccessfully'),
            type: 'success',
          })
        } else {
          await $post(apiPrefix('courses'), payload)
          show({
            message: t('messages.info.createdSuccessfully'),
            type: 'success',
          })

          // Create course logic
        }
        setAugmented(false)
        router.push({
          path: '/settings/courses',
        })
      } catch (error) {
        console.error(error)
      }
    }
  )

  const onReset = () => {
    if (isEdit) {
      if (courseData.value) {
        useFormRes.setValues(courseData.value)
      }
      return
    }
    router.back()
  }

  return {
    useFormRes,
    titleField,
    descriptionField,
    majorField,
    onSubmit,
    onReset,
  }
}

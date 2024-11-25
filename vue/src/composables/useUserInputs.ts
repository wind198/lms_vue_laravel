import { useI18n } from 'vue-i18n'
import { IUser, IUserCoreField, IUserType } from '../types/entities/user.entity'
import { date, number, object, string } from 'yup'
import {
  EDUCATION_BACKGROUND_LIST,
  GENDER_LIST,
  IEducationBackground,
  IGender,
  IS_DEV,
} from '../utils/constants'
import { useField, useForm, useResetForm } from 'vee-validate'
import dayjs, { Dayjs } from 'dayjs'
import { useToastStore } from '@/stores/toast'
import { useQueryClient } from '@tanstack/vue-query'
import useApiHttpClient from '@/composables/useHttpClient'
import { apiPrefix, getOneUrl } from '@/utils/helpers'
import useGetOne from '@/composables/useGetOne'
import { faker } from '@faker-js/faker'
import useIsEditPage from '@/composables/useIsEditPage'

export type IUserForm = IUserCoreField

export type IUpdateUserForm = Partial<IUserForm>

type IOptions = {
  resource: string
  resourcePlural?: string
  user_type: IUserType
}

export default function useUserInputs(options: IOptions) {
  const { resource, user_type, resourcePlural = resource + 's' } = options

  const { t } = useI18n()

  const { params } = useRoute()

  const stringRequiredSchema = string().required(
    t('messages.validations.required')
  )

  const { isEdit } = useIsEditPage()

  const getRandomUser = (): IUserCoreField => {
    const firstName = faker.person.firstName()
    const lastName = faker.person.lastName()
    const fullName = `${firstName} ${lastName}`
    return {
      first_name: firstName,
      last_name: lastName,
      full_name: fullName,
      education_background: 'HIGH_SCHOOL',
      email: faker.internet.email(),
      gender: 'FEMALE',
      phone: faker.phone.number(),
      address: faker.location.streetAddress(),
      dob: faker.date.past(),
      user_type,
    }
  }

  const validationSchema = object({
    email: stringRequiredSchema.email(t('messages.validations.invalidEmail')),
    first_name: stringRequiredSchema,
    last_name: stringRequiredSchema,
    phone: string(),
    address: string(),
    gender: stringRequiredSchema.oneOf(GENDER_LIST),
    education_background: stringRequiredSchema.oneOf(EDUCATION_BACKGROUND_LIST),
    dob: date().required(t('messages.validations.required')),
    generation_id: number().notRequired(),
  })

  // @ts-expect-error
  const recordId = params['id']

  const { data: userData } = useGetOne<IUser>({
    id: recordId,
    resource,
  })

  const initialValues = computed(() => {
    const state = history.state
    return isEdit ? state.recordData : IS_DEV ? getRandomUser() : undefined
  })

  // Form and fields
  const useFormRes = useForm<IUserForm>({
    validationSchema,
    initialValues: initialValues?.value,
  })
  const emailField = useField<string>('email')
  const firstNameField = useField<string>('first_name')
  const lastNameField = useField<string>('last_name')
  const phoneField = useField<string>('phone')
  const addressField = useField<string>('address')
  const genderField = useField<IGender>('gender')
  const educationBackgroundField = useField<IEducationBackground>(
    'education_background'
  )
  const dobField = useField<Dayjs>('dob')
  const generationField = useField<number>('generation_id')

  const { show } = useToastStore()

  const router = useRouter()

  const queryClient = useQueryClient()

  const { $post, $patch } = useApiHttpClient()

  const onSubmit = useFormRes.handleSubmit(async (_payload: IUserCoreField) => {
    const { dob, ...o } = _payload
    const $payload = {
      ...o,
      dob: dayjs(dob).format('YYYY-MM-DD'),
    }
    try {
      if (!isEdit) {
        await $post(apiPrefix(resourcePlural), $payload)
        show({
          message: t('messages.info.createdSuccessfully'),
          type: 'success',
        })
      } else if (recordId) {
        await $patch(getOneUrl(resourcePlural, recordId), $payload)
        show({
          message: t('messages.info.updatedSuccessfully'),
          type: 'success',
        })
      }

      router.push('/settings/' + resourcePlural)
    } catch (error) {
      console.error('Error creating user:', error)
    } finally {
      queryClient.invalidateQueries([resourcePlural] as any)
    }
  })

  const onReset = () => {
    if (isEdit) {
      if (userData.value) {
        useFormRes.setValues(userData.value)
      }
      return
    }
    router.back()
  }

  watchEffect(() => {
    if (userData.value && !useFormRes.meta.value.dirty) {
      useFormRes.setValues(userData.value)
    }
  })

  return {
    useFormRes,
    onReset,
    onSubmit,
    emailField,
    firstNameField,
    lastNameField,
    phoneField,
    addressField,
    genderField,
    educationBackgroundField,
    dobField,
    generationField,
  }
}

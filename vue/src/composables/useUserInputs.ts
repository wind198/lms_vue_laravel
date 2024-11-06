import { useI18n } from 'vue-i18n'
import { IUser, IUserCoreField } from '../types/entities/user.entity'
import { date, object, string } from 'yup'
import {
  EDUCATION_BACKGROUND_LIST,
  GENDER_LIST,
  IEducationBackground,
  IGender,
} from '../utils/constants'
import { useField, useForm } from 'vee-validate'
import { Dayjs } from 'dayjs'

export type IUserForm = IUserCoreField

export type IUpdateUserForm = Partial<IUserForm>

export default function useUserInputs(initialValues?: IUserCoreField) {
  const { t } = useI18n()
  const stringRequiredSchema = string().required(
    t('messages.validations.required')
  )

  const validationSchema = object({
    email: stringRequiredSchema.email(t('messages.validations.invalidEmail')),
    first_name: stringRequiredSchema,
    last_name: stringRequiredSchema,
    phone: string(),
    address: string(),
    gender: stringRequiredSchema.oneOf(GENDER_LIST),
    education_background: stringRequiredSchema.oneOf(EDUCATION_BACKGROUND_LIST),
    dob: date().required(t('messages.validations.required')),
  })

  // Form and fields
  const { handleSubmit, handleReset } = useForm<IUserForm>({
    validationSchema,
    initialValues,
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

  return {
    handleSubmit,
    handleReset,
    emailField,
    firstNameField,
    lastNameField,
    phoneField,
    addressField,
    genderField,
    educationBackgroundField,
    dobField,
  }
}

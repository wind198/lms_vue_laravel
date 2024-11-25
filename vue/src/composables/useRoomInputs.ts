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
import { IRoom, IRoomCoreField } from '@/types/entities/room.entity'

export type IRoomForm = IRoomCoreField

export type IUpdateRoomForm = Partial<IRoomForm>

const getRandomRoomInputs = (): IRoomCoreField => {
  return {
    title: faker.word.words(5),
    description: faker.word.words(20),
    address: faker.word.words(20),
  }
}

export default function useRoomInputs() {
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
    address: string().max(
      MAX_ADDRESS_LENGTH,
      t('messages.validations.maxLength', { max: MAX_ADDRESS_LENGTH })
    ),
  })

  const initialValues = computed(() => {
    const state = history.state
    return isEdit
      ? state.recordData
      : IS_DEV
      ? getRandomRoomInputs()
      : undefined
  })

  const { data: roomData } = useGetOne<IRoom>({
    id: recordId,
    resource: 'room',
  })

  watchEffect(() => {
    if (roomData.value && !useFormRes.meta.value.dirty) {
      useFormRes.setValues(roomData.value)
    }
  })

  // Form and fields
  const useFormRes = useForm<IRoomForm>({
    validationSchema,
    initialValues: initialValues?.value,
  })
  const titleField = useField<string>('title')
  const descriptionField = useField<string>('description')
  const addressField = useField<string>('room')

  const { $patch, $post } = useApiHttpClient()

  const { show } = useToastStore()

  const router = useRouter()

  const { setAugmented } = useQueryParamsStore()

  const onSubmit = useFormRes.handleSubmit(async (payload: IRoomCoreField) => {
    try {
      if (isEdit) {
        // Update room logic
        await $patch(getOneUrl('rooms', recordId), payload)
        show({
          message: t('messages.info.savedSuccessfully'),
          type: 'success',
        })
      } else {
        await $post(apiPrefix('rooms'), payload)
        show({
          message: t('messages.info.createdSuccessfully'),
          type: 'success',
        })

        // Create room logic
      }
      setAugmented(false)
      router.push({
        path: '/settings/rooms',
      })
    } catch (error) {
      console.error(error)
    }
  })

  const onReset = () => {
    if (isEdit) {
      if (roomData.value) {
        useFormRes.setValues(roomData.value)
      }
      return
    }
    router.back()
  }

  return {
    useFormRes,
    titleField,
    descriptionField,
    addressField,
    onSubmit,
    onReset,
  }
}

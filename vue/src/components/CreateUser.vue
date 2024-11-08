<script setup lang="ts">
import { faker } from '@faker-js/faker'
import { useI18n } from 'vue-i18n'
import useApiHttpClient from '../composables/useHttpClient.js'
import useUserInputs from '../composables/useUserInputs.js'
import { useToastStore } from '../stores/toast.js'
import { IUserCoreField, IUserType } from '../types/entities/user.entity.js'
import { apiPrefix } from '../utils/helpers.js'
import {
  EDUCATION_BACKGROUND_LIST,
  GENDER_LIST,
  IS_DEV,
} from '../utils/constants.js'
import dayjs from 'dayjs'
import useQueryParamsStore from '@/stores/query.js'
import { useQueryClient } from '@tanstack/vue-query'
import useGenerations from '@/composables/useGenerations.js'
import { IMethod } from '@/types/common.type.js'

const props = defineProps<{
  userType: IUserType
  method?: IMethod
}>()

const { t } = useI18n()
const { $post } = useApiHttpClient()

const isEdit = computed(() => props.method !== 'POST')

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
    user_type: props.userType,
  }
}

const initialValues = computed(() => (IS_DEV ? getRandomUser() : undefined))

const {
  addressField,
  dobField,
  educationBackgroundField,
  emailField,
  firstNameField,
  genderField,
  handleReset,
  handleSubmit,
  lastNameField,
  phoneField,
  generationField,
} = useUserInputs(initialValues)

const { show } = useToastStore()

const router = useRouter()

const resource = computed(() => props.userType + 's')

const queryClient = useQueryClient()

const createUser = async (payload: IUserCoreField) => {
  try {
    const { dob, ...o } = payload

    await $post(apiPrefix(resource.value), {
      ...o,
      dob: dayjs(dob).format('YYYY-MM-DD'),
    })
    show({
      message: t('messages.info.createdSuccessfully'),
      type: 'success',
    })
    router.push('/settings/' + resource.value)
  } catch (error) {
    console.error('Error creating user:', error)
  } finally {
    queryClient.invalidateQueries([resource.value] as any)
  }
}

const onSubmit = handleSubmit(async (values) => {
  console.log('Form submitted with values:', values)
  // Handle form submission, e.g., API call

  await createUser(values)
})

const genderItemList = GENDER_LIST.map((i) => ({
  title: t('nouns.' + i.toLowerCase()),
  value: i,
}))
const educationBackgroundItemList = EDUCATION_BACKGROUND_LIST.map((i) => ({
  title: t('nouns.' + i.toLowerCase()),
  value: i,
}))

const { data: generationData, isLoading: isLoadingGenerations } =
  useGenerations()
const generationItems = computed(() => {
  return generationData.value?.data.map((g) => ({
    title: g.title,
    value: g.id,
  }))
})
</script>

<template>
  <VForm @submit.prevent="onSubmit" @reset.prevent="handleReset">
    <VSheet class="pa-3">
      <v-text-field
        :label="t('nouns.email')"
        type="email"
        :error-messages="emailField.errorMessage.value"
        v-model="emailField.value.value"
      />
      <div class="d-flex align-baseline">
        <v-text-field
          :label="t('nouns.firstName')"
          :error-messages="firstNameField.errorMessage.value"
          v-model="firstNameField.value.value"
          class="mr-3"
        />
        <v-text-field
          :label="t('nouns.lastName')"
          :error-messages="lastNameField.errorMessage.value"
          v-model="lastNameField.value.value"
        />
      </div>
      <v-select
        :label="t('nouns.gender')"
        v-model="genderField.value.value"
        :error-messages="genderField.errorMessage.value"
        :items="genderItemList"
      />
      <VSelect
        v-if="userType === 'student'"
        :items="generationItems"
        v-model="generationField.value.value"
        :error-messages="generationField.errorMessage.value"
      />
      <v-text-field
        :label="t('nouns.phone')"
        :error-messages="phoneField.errorMessage.value"
        v-model="phoneField.value.value"
        type="tel"
      />
      <v-text-field
        :label="t('nouns.address')"
        :error-messages="addressField.errorMessage.value"
        v-model="addressField.value.value"
      />

      <v-select
        :label="t('nouns.educationLevel')"
        v-model="educationBackgroundField.value.value"
        :error-messages="educationBackgroundField.errorMessage.value"
        :items="educationBackgroundItemList"
      />
      <VDateInput
        :label="t('nouns.dob')"
        v-model="dobField.value.value"
        :error-messages="dobField.errorMessage.value"
      />
      <div class="d-flex w-full">
        <v-spacer />
        <v-btn variant="flat" type="reset">
          {{ t('actions.reset') }}
        </v-btn>
        <v-btn type="submit" color="primary" variant="flat">
          {{ t('actions.save') }}
        </v-btn>
      </div>
    </VSheet>
  </VForm>
</template>

<style scoped>
.error {
  color: red;
  font-size: 0.875rem;
}
</style>

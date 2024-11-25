<script setup lang="ts">
import useGenerations from '@/composables/useGenerations.js'
import { camelCase } from 'lodash-es'
import { useI18n } from 'vue-i18n'
import useUserInputs from '../composables/useUserInputs.js'
import { IUser, IUserType } from '../types/entities/user.entity.js'
import { EDUCATION_BACKGROUND_LIST, GENDER_LIST } from '../utils/constants.js'
import useIsEditPage from '@/composables/useIsEditPage.js'

const props = defineProps<{
  userType: IUserType
}>()

const { t } = useI18n()

const { isEdit } = useIsEditPage()

const {
  addressField,
  dobField,
  educationBackgroundField,
  emailField,
  firstNameField,
  genderField,
  lastNameField,
  phoneField,
  generationField,
  onSubmit,
  onReset,
  useFormRes,
} = useUserInputs({
  resource: props.userType,
  user_type: props.userType,
})

const genderItemList = GENDER_LIST.map((i) => ({
  title: t('nouns.' + i.toLowerCase()),
  value: i,
}))
const educationBackgroundItemList = EDUCATION_BACKGROUND_LIST.map((i) => ({
  title: t('nouns.' + camelCase(i.toLowerCase())),
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

const basicTabErr = computed(() => {
  const basicItems: (keyof IUser)[] = [
    'first_name',
    'last_name',
    'email',
    'education_background',
    'generation_id',
    'gender',
  ]

  console.log(useFormRes.errors.value)

  return basicItems.some((i) => !!useFormRes.errors.value[i])
})

const advancedTabErr = computed(() => {
  const advancedItems: (keyof IUser)[] = ['phone', 'address']

  console.log(useFormRes.errors.value)

  return advancedItems.some((i) => !!useFormRes.errors.value[i])
})
</script>

<template>
  <VForm @submit.prevent="onSubmit" @reset.prevent="onReset">
    <VSheet class="pa-3">
      <FormTabs
        default-tab="basic"
        :tabs="[
          {
            value: 'basic',
            label: t('nouns.basicInfo'),
            isError: basicTabErr,
          },
          {
            value: 'advanced',
            label: t('adjs.advanced'),
            isError: advancedTabErr,
          },
        ]"
      >
        <template #basic>
          <div class="tab-content">
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
            <v-text-field
              :label="t('nouns.email')"
              type="email"
              :error-messages="emailField.errorMessage.value"
              v-model="emailField.value.value"
              prepend-icon="mdi-email"
            />
            <v-select
              :label="t('nouns.gender')"
              v-model="genderField.value.value"
              :error-messages="genderField.errorMessage.value"
              :items="genderItemList"
              prepend-icon="mdi-human-female"
            />
            <VDateInput
              :label="t('nouns.dob')"
              v-model="dobField.value.value"
              :error-messages="dobField.errorMessage.value"
            />
            <VSelect
              v-if="userType === 'student'"
              :label="t('nouns.generation')"
              :items="generationItems"
              v-model="generationField.value.value"
              :loading="isLoadingGenerations"
              :error-messages="generationField.errorMessage.value"
              prepend-icon="mdi-account-group"
            />

            <v-select
              prepend-icon="mdi-school"
              :label="t('nouns.educationBackground')"
              v-model="educationBackgroundField.value.value"
              :error-messages="educationBackgroundField.errorMessage.value"
              :items="educationBackgroundItemList"
            />
          </div>
        </template>
        <template #advanced>
          <div class="tab-content">
            <v-text-field
              :label="t('nouns.phone')"
              :error-messages="phoneField.errorMessage.value"
              v-model="phoneField.value.value"
              type="tel"
              prepend-icon="mdi-phone"
            />
            <v-text-field
              prepend-icon="mdi-map-marker"
              :label="t('nouns.address')"
              :error-messages="addressField.errorMessage.value"
              v-model="addressField.value.value"
            />
          </div>
        </template>
      </FormTabs>
      <CreateFormActionsToolbar :is-edit="isEdit" />
    </VSheet>
  </VForm>
</template>

<style scoped>
.error {
  color: red;
  font-size: 0.875rem;
}
.tab-content {
  max-width: 800px;
  margin: auto;
}
</style>

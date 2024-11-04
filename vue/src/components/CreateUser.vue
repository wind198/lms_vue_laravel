<script setup lang="ts">
import { useI18n } from 'vue-i18n'
import useApiHttpClient from '../composables/useHttpClient.js'
import useUserInputs from '../composables/useUserInputs.js'
import { useToastStore } from '../stores/toast.js'
import { IUserType } from '../types/entities/user.entity.js'
import { apiPrefix } from '../utils/helpers.js'
import { EDUCATION_BACKGROUND_LIST, GENDER_LIST } from '../utils/constants.js'

const props = defineProps<{
  userType: IUserType
}>()

const { t } = useI18n()
const { $post } = useApiHttpClient()

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
} = useUserInputs()

const { show } = useToastStore()

const onSubmit = handleSubmit(async (values) => {
  console.log('Form submitted with values:', values)
  // Handle form submission, e.g., API call

  await $post(apiPrefix(props.userType), values)
})
</script>

<template>
  <VForm @submit="onSubmit" @reset="handleReset">
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
        :items="GENDER_LIST"
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
        :v-model="addressField.value.value"
      />
      <v-select
        :label="t('nouns.educationLevel')"
        v-model="educationBackgroundField.value.value"
        :error-messages="educationBackgroundField.errorMessage.value"
        :items="EDUCATION_BACKGROUND_LIST"
      />
      <VDateInput
        :label="t('nouns.dob')"
        v-model="dobField.value.value"
        :error-messages="dobField.errorMessage.value"
      />
      <div class="d-flex w-full">
        <v-spacer />
        <v-btn type="button" variant="flat">
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

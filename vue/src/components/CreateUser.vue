<script setup lang="ts">
import { faker } from '@faker-js/faker'
import { useI18n } from 'vue-i18n'
import useApiHttpClient from '../composables/useHttpClient.js'
import useUserInputs from '../composables/useUserInputs.js'
import { useToastStore } from '../stores/toast.js'
import {
  IUser,
  IUserCoreField,
  IUserType,
} from '../types/entities/user.entity.js'
import { apiPrefix, getOneUrl, removeTrailingSlash } from '../utils/helpers.js'
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
import { camelCase } from 'lodash-es'
import useGetOne from '@/composables/useGetOne.js'

const props = defineProps<{
  userType: IUserType
}>()

const { t } = useI18n()

const { path } = useRoute()

const isEdit = computed(() => removeTrailingSlash(path).endsWith('update'))

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
} = useUserInputs({
  isEdit: isEdit.value,
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
</script>

<template>
  <VForm @submit.prevent="onSubmit" @reset.prevent="onReset">
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
        :label="t('nouns.generation')"
        :items="generationItems"
        v-model="generationField.value.value"
        :loading="isLoadingGenerations"
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
        :label="t('nouns.educationBackground')"
        v-model="educationBackgroundField.value.value"
        :error-messages="educationBackgroundField.errorMessage.value"
        :items="educationBackgroundItemList"
      />
      <VDateInput
        :label="t('nouns.dob')"
        v-model="dobField.value.value"
        :error-messages="dobField.errorMessage.value"
      />
      <CreateFormActionsToolbar :is-edit="isEdit" />
    </VSheet>
  </VForm>
</template>

<style scoped>
.error {
  color: red;
  font-size: 0.875rem;
}
</style>

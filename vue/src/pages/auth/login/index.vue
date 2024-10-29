<script setup lang="ts">
import { useField, useForm } from 'vee-validate'
import { object, string } from 'yup'
import type { LayoutKey } from '../../../.nuxt/types/layouts.js'
import { useMutation } from '@tanstack/vue-query'
import useHttpClient from '../../../composables/useHttpClient.js'
import { IUser } from '../../../types/entities/user.entity.js'
import { useI18n } from 'vue-i18n'
import { useAppStore } from '../../../stores/app.js'
import authStore from '../../../stores/auth.js'

type IFormData = {
  email: string
  password: string
}

const { t } = useI18n()

const welcomeMsg = t('messages.info.welcome')
const subtitleMsg = t('messages.info.pleaseLogin')

const validationSchema = object({
  email: string()
    .required(t('messages.validations.required'))
    .email(t('messages.validations.email')),
  password: string().required(),
})

const { handleSubmit, handleReset } = useForm<IFormData>({
  initialValues: {
    email: '',
    password: '',
  },
  validationSchema,
})

const {
  value: email,
  errorMessage: emailErrorMessages,
  errors: emailErrors,
} = useField('email')
const {
  value: password,
  errorMessage: passwordErrorMessages,
  errors: passwordErrors,
} = useField('password')

const showingPasswd = ref(false)

const { $post } = useHttpClient()

const { login } = authStore()

const { status, mutateAsync } = useMutation({
  mutationFn: async (payload: any) => {
    const { data } = await $post<IFormData, { user: IUser; token: string }>(
      'login',
      payload
    )

    login(data)
  },
})

const onSubmit = () => {
  handleSubmit(async (data) => {
    try {
      await sendLoginRequest()
    } catch (error) {
      console.error(error)
      handleReset()
    }
  })
}
</script>
<template>
  <v-form class="login-form pa-3" @submit.prevent="onSubmit">
    <v-card>
      <v-card-title>{{ welcomeMsg }}</v-card-title>
      <v-card-subtitle>{{ subtitleMsg }}</v-card-subtitle>
      <v-card-text>
        <v-text-field
          v-model="email"
          :error="!!emailErrors.length"
          :error-messages="emailErrorMessages"
          label="Email"
          prepend-inner-icon="mdi-mail"
        />

        <v-text-field
          v-model="password"
          :type="showingPasswd ? 'text' : 'password'"
          label="Password"
          :error="!!passwordErrors.length"
          :error-messages="passwordErrorMessages"
          prepend-inner-icon="mdi-lock"
        />
      </v-card-text>
      <v-card-actions>
        <v-btn
          block
          color="primary"
          type="submit"
          variant="flat"
          :elevation="0"
          :loading="status === 'pending'"
          >{{ t('actions.login') }}</v-btn
        >
      </v-card-actions>
    </v-card>
  </v-form>
</template>
<style scoped>
.login-form {
  max-width: 800px;
}
</style>
<route lang="yaml">
meta:
  layout: home
</route>

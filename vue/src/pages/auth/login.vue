<script setup lang="ts">
import { useField, useForm } from 'vee-validate'
import { object, string } from 'yup'
import { useMutation } from '@tanstack/vue-query'
import useApiHttpClient from '../../composables/useHttpClient.js'
import { IUser } from '../../types/entities/user.entity.js'
import { useI18n } from 'vue-i18n'
import useAuthStore from '../../stores/auth.js'
import { apiPrefix, extractXsrfToken } from '../../utils/helpers.js'

type IFormData = {
  email: string
  password: string
}

const { t } = useI18n()

const router = useRouter()

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
    email: 'tuanbk1908@gmail.com',
    password: 'abc123',
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

const { $post, $get } = useApiHttpClient()

const { login } = useAuthStore()

const { status, mutateAsync: sendLoginRequest } = useMutation({
  mutationFn: async (payload: any) => {
    await $get('/sanctum/csrf-cookie')
    await $post('/login', payload).then(async () => {
      const { data } = await $get(apiPrefix('user'))
      login({ user: data })
      router.push('/settings/students')
    })
  },
})

const onSubmit = () => {
  handleSubmit(
    async (data) => {
      try {
        await sendLoginRequest(data)
      } catch (error) {
        console.error(error)
        handleReset()
      }
    },
    (ctx) => {
      console.error(ctx.errors)
    }
  )()
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
          :error="!!passwordErrors.length"
          :error-messages="passwordErrorMessages"
          label="Password"
          prepend-inner-icon="mdi-lock"
          :type="showingPasswd ? 'text' : 'password'"
        />
      </v-card-text>
      <v-card-actions>
        <v-btn
          block
          color="primary"
          :elevation="0"
          :loading="status === 'pending'"
          type="submit"
          variant="flat"
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
  layout: login
</route>

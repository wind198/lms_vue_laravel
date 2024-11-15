<script setup lang="ts">
import useFormatDateTime from '@/composables/useFormatDateTime.js'
import { IUser } from '@/types/entities/user.entity.js'
import { camelCase } from 'lodash-es'
import { useI18n } from 'vue-i18n'

const props = defineProps<{
  userData: IUser
}>()

const fieldsToRenders: (keyof IUser)[] = [
  'full_name',
  'user_type',
  ...(props.userData.user_type === 'student' ? (['generation'] as const) : []),
  'email',
  'gender',
  'phone',
  'dob',
  'education_background',
  'created_at',
]

const { t } = useI18n()

const { formatDateCommon } = useFormatDateTime()

const renderData = (f: keyof IUser) => {
  const val = props.userData[f]
  switch (f) {
    case 'created_at':
    case 'dob':
      return formatDateCommon(val as string)
    case 'education_background':
    case 'gender':
    case 'user_type':
      return t(`nouns.${camelCase(val as string)}`)

    default:
      return val
  }
}
</script>
<template>
  <dl class="user-basic-info pa-2">
    <template v-for="f in fieldsToRenders" :key="f">
      <dt class="text-subtitle-2 mb-1">{{ t(`nouns.${camelCase(f)}`) }}</dt>
      <dd class="text-body-2 mb-2" v-if="f === 'generation'">
        <RouterLink
          :to="`/settings/generations/${userData.generation.id}`"
          v-if="userData.generation?.id"
        >
          {{ userData.generation?.title }}
        </RouterLink>
        <span v-else>
          {{ t('others.none') }}
        </span>
      </dd>
      <dd v-else class="text-body-2 mb-2">
        {{ renderData(f) }}
      </dd>
    </template>
  </dl>
</template>
<style scoped></style>

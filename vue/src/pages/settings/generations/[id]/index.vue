<script setup lang="ts">
import useFormatDateTime from '@/composables/useFormatDateTime.js'
import useGetOne from '@/composables/useGetOne.js'
import { IStringOrNumber } from '@/types/common.type.js'
import { IGeneration } from '@/types/entities/generation.entity.js'
import { camelCase, cloneDeep } from 'lodash-es'
import { useI18n } from 'vue-i18n'

definePage({
  meta: {
    isBreadcrumb: true,
    label: 'nouns.detail',
    title: ['others.viewDetail', { entity: 'nouns.student' }],
  },
})

const fieldsToRenders: (keyof IGeneration)[] = [
  'title',
  'description',
  'year',
  'students_count',
  'created_at',
]

const resourcePlural = 'generations'

const currentRoute = useRoute()

const userId = ref<IStringOrNumber | undefined>(currentRoute.params.id)

const { data: recordData, isLoading } = useGetOne<IGeneration>({
  id: userId,
  resource: 'generation',
  placeholderData: history.state.userData,
})

const { formatDateCommon } = useFormatDateTime()

const renderLabel = (f: keyof IGeneration) => {
  if (f === 'students_count') {
    return t('nouns.count', { entity: t('nouns.student') })
  }
  return t(`nouns.${camelCase(f)}`)
}

const renderData = (f: keyof IGeneration) => {
  const val = recordData.value?.[f]
  switch (f) {
    case 'created_at':
      return formatDateCommon(val as string)

    default:
      return val
  }
}

const { t } = useI18n()

const router = useRouter()

const onClickEditBtn = () => {
  router.push({
    path: `/settings/${resourcePlural}/${userId.value}/update`,
    state: {
      userData: cloneDeep(recordData.value ?? {}),
    },
  })
}
</script>
<template>
  <div class="show-student-page">
    <VSheet class="pa-3">
      <VSkeletonLoader type="heading, card" v-if="isLoading" />
      <div v-else-if="recordData">
        <VToolbar class="bg-transparent">
          <VSpacer />
          <VBtn
            color="primary"
            variant="flat"
            :to="`/settings/${resourcePlural}/${userId}/update`"
            @click.prevent="onClickEditBtn"
          >
            {{ t(`actions.update`) }}</VBtn
          >
        </VToolbar>
      </div>
      <dl class="generation-info pa-2">
        <template v-for="f in fieldsToRenders" :key="f">
          <dt class="text-subtitle-2 mb-1">{{ renderLabel(f) }}</dt>
          <dd class="text-body-2 mb-2">
            {{ renderData(f) }}
          </dd>
        </template>
      </dl>
    </VSheet>
  </div>
</template>
<style scoped></style>

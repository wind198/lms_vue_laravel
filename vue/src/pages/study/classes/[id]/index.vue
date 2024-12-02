<script setup lang="ts">
import useFormatDateTime from '@/composables/useFormatDateTime.js'
import useGetOne from '@/composables/useGetOne.js'
import { IStringOrNumber } from '@/types/common.type.js'
import { IClass } from '@/types/entities/class.entity.js'
import { camelCase, cloneDeep } from 'lodash-es'
import { useI18n } from 'vue-i18n'

definePage({
  meta: {
    isBreadcrumb: true,
    label: 'nouns.detail',
    title: ['others.viewDetail', { entity: 'nouns.class' }],
  },
})

const fieldsToRenders: (keyof IClass)[] = [
  'title',
  'description',
  'course',
  'created_at',
]

const resourcePlural = 'classes'

const currentRoute = useRoute()

// @ts-expect-error
const recordId = ref<IStringOrNumber | undefined>(currentRoute.params.id)

const { data: recordData, isLoading } = useGetOne<IClass>({
  id: recordId,
  resource: 'class',
  resourcePlular: 'classes',
  placeholderData: history.state.recordData,
})

const { formatDateCommon } = useFormatDateTime()

const renderLabel = (f: keyof IClass) => {
  return t(`nouns.${camelCase(f)}`)
}

const renderData = (f: keyof IClass) => {
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
    path: `/study/${resourcePlural}/${recordId.value}/update`,
    state: {
      recordData: cloneDeep(recordData.value ?? {}),
    },
  })
}
</script>
<template>
  <div class="show-class-page">
    <VSheet class="pa-3">
      <VSkeletonLoader type="heading, card" v-if="isLoading" />
      <div v-else-if="recordData">
        <VToolbar class="bg-transparent">
          <VSpacer />
          <VBtn
            color="primary"
            variant="flat"
            :to="`/settings/${resourcePlural}/${recordId}/update`"
            @click.prevent="onClickEditBtn"
          >
            {{ t(`actions.update`) }}</VBtn
          >
        </VToolbar>
        <dl class="class-basic-info pa-2">
          <template v-for="f in fieldsToRenders" :key="f">
            <dt class="text-subtitle-2 mb-1">
              {{ t(`nouns.${camelCase(f)}`) }}
            </dt>
            <dd class="text-body-2 mb-2" v-if="f === 'course'">
              <RouterLink
                :to="`/settings/generations/${recordData?.course?.id}`"
                v-if="recordData?.course?.id"
              >
                {{ recordData?.course?.title }}
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
      </div>
    </VSheet>
  </div>
</template>
<style scoped></style>

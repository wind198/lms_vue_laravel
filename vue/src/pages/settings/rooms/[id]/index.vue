<script setup lang="ts">
import useFormatDateTime from '@/composables/useFormatDateTime.js'
import useGetOne from '@/composables/useGetOne.js'
import { IStringOrNumber } from '@/types/common.type.js'
import { IRoom } from '@/types/entities/room.entity.js'
import { camelCase, cloneDeep } from 'lodash-es'
import { useI18n } from 'vue-i18n'

definePage({
  meta: {
    // isBreadcrumb: true,
    // label: ['others.viewDetail', { entity: 'nouns.room' }],
    // title: ['others.viewDetail', { entity: 'nouns.room' }],
  },
})

const fieldsToRenders: (keyof IRoom)[] = [
  'title',
  'description',
  'address',
  'created_at',
]

const resourcePlural = 'rooms'

const currentRoute = useRoute()

// @ts-expect-error
const recordId = ref<IStringOrNumber | undefined>(currentRoute.params.id)

const { data: recordData, isLoading } = useGetOne<IRoom>({
  id: recordId,
  resource: 'room',
  placeholderData: history.state.recordData,
})

const { formatDateCommon } = useFormatDateTime()

const renderLabel = (f: keyof IRoom) => {
  return t(`nouns.${camelCase(f)}`)
}

const renderData = (f: keyof IRoom) => {
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
    path: `/settings/${resourcePlural}/${recordId.value}/update`,
    state: {
      recordData: cloneDeep(recordData.value ?? {}),
    },
  })
}
</script>
<template>
  <div class="show-room-page">
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
      </div>
      <dl class="room-info pa-2">
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

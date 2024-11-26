<script setup lang="ts">
import ShowUserBasicInfo from '@/components/ShowUserBasicInfo.vue'
import useGetOne from '@/composables/useGetOne.js'
import { IUser, IUserType } from '@/types/entities/user.entity.js'
import { cloneDeep } from 'lodash-es'
import { useI18n } from 'vue-i18n'

const props = defineProps<{
  user_type: IUserType
}>()

const currentRoute = useRoute()

const recordId = ref<string | undefined>(currentRoute.params.id)

const { t } = useI18n()

const {
  data: recordData,
  isLoading,
  isNotFoundErr,
} = useGetOne<IUser>({
  id: recordId,
  resource: props.user_type,
  placeholderData: history.state.recordData,
})

const tabs = [
  {
    value: 'basic',
    title: t(`nouns.basicInfo`),
  },
]

const router = useRouter()

const resourcePlural = computed(() => props.user_type + 's')

const onClickEditBtn = () => {
  router.push({
    path: `/settings/${resourcePlural.value}/${recordId.value}/update`,
    state: {
      recordData: cloneDeep(recordData.value ?? {}),
    },
  })
}
</script>
<template>
  <VSheet class="pa-3">
    <NotFound v-if="isNotFoundErr" />
    <VSkeletonLoader type="heading, card" v-else-if="isLoading" />
    <div v-else-if="recordData">
      <VToolbar class="bg-transparent">
        <VTabs>
          <VTab
            density="compact"
            v-for="t in tabs"
            :key="t.value"
            :value="t.value"
          >
            {{ t.title }}
          </VTab>
        </VTabs>
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
      <ShowUserBasicInfo :recordData="recordData" />
    </div>
  </VSheet>
</template>
<style scoped></style>

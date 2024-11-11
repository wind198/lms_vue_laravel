<script setup lang="ts">
import ShowUserBasicInfo from '@/components/ShowUserBasicInfo.vue'
import useGetOne from '@/composables/useGetOne.js'
import { IUser, IUserType } from '@/types/entities/user.entity.js'
import { useI18n } from 'vue-i18n'

const props = defineProps<{
  user_type: IUserType
}>()

const currentRoute = useRoute()

const userId = toRef(currentRoute.params.id)

const { t } = useI18n()

const { data: userData, isLoading } = useGetOne<IUser>({
  id: userId,
  resource: props.user_type,
})

const tabs = [
  {
    value: 'basic',
    title: t(`nouns.basicInfo`),
  },
]
</script>
<template>
  <VSheet class="pa-3">
    <VSkeletonLoader type="heading, card" v-if="isLoading" />
    <div v-else-if="userData">
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
          :to="`/settings/students/${userId}/update`"
        >
          {{ t(`actions.update`) }}</VBtn
        >
      </VToolbar>
      <ShowUserBasicInfo :user-data="userData" />
    </div>
  </VSheet>
</template>
<style scoped></style>

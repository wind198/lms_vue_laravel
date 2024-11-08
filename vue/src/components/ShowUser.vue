<script setup lang="ts">
import useGetOne from '@/composables/useGetOne.js'
import { IUser, IUserType } from '@/types/entities/user.entity.js'

const props = defineProps<{
  user_type: IUserType
}>()

const currentRoute = useRoute()

const userId = toRef(currentRoute.params.id)

const { data: userData, isLoading } = useGetOne<IUser>({
  id: userId,
  resource: props.user_type,
})
</script>
<template>
  <VSheet class="pa-3"> 
    <VSkeletonLoader 
    type=''
    />
  </VSheet>
</template>
<style scoped></style>

<script setup lang="ts">
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

const reload = () => {
  window.location.reload()
}

const router = useRouter()
</script>
<template>
  <FillVerticalSpace
    class="not-found d-flex flex-column align-center justify-center"
  >
    <VIcon class="text-h1" icon="mdi-cloud-off-outline" />
    <slot> {{ t('messages.error.notFound') }} </slot>
    <div class="mt-4 py-2">
      <VBtn flat class="mr-2" @click="router.back">
        {{ t('actions.back') }}
      </VBtn>
      <VBtn color="primary" flat class="btn btn-primary" @click="reload">
        {{ t('actions.reload') }}
      </VBtn>
    </div>
  </FillVerticalSpace>
</template>
<style scoped></style>

<script setup lang="ts">
import { storeToRefs } from 'pinia'
import { useCommonStuffStore } from '../stores/common.js'
import { useI18n } from 'vue-i18n'
import { useToastStore } from '../stores/toast.js'

const store = useCommonStuffStore()

const { t } = useI18n()

const { longTextDialog } = storeToRefs(store)

const hasCopy = !!window.navigator.clipboard.read

const { show } = useToastStore()

const addToClipboard = async () => {
  await window.navigator.clipboard.writeText(longTextDialog.value.text)
  show({ message: t('messages.info.copied') })
}
</script>
<template v-slot:default="{ isActive }">
  <v-dialog max-width="500" :model-value="longTextDialog.isOpen">
    <v-card :title="longTextDialog.title ?? 'Long text'">
      <v-card-text>
        {{ longTextDialog.text }}
      </v-card-text>

      <v-card-actions>
        <v-btn
          v-if="hasCopy"
          :text="t('actions.copy')"
          @click="addToClipboard"
        />
        <v-spacer />
        <v-btn :text="t('actions.close')" @click="store.closeLongTextDialog" />
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>
<style scoped></style>

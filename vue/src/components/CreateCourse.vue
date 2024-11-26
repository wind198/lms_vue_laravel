<script setup lang="ts">
import useCourseInputs from '@/composables/useCourseInputs'
import useGetList from '@/composables/useGetList.js'
import { IMajor } from '@/types/entities/major.entity.js'
import { removeTrailingSlash } from '@/utils/helpers.js'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

const { path, params } = useRoute()

const isEdit = computed(() => removeTrailingSlash(path).endsWith('update'))

const { majorField, onSubmit, onReset, descriptionField, titleField } =
  useCourseInputs()

const queryMajorPage = ref(1)
const queryMajorPerPage = ref(1e5)

const searchMajorString = ref('')

const queryMajorFilter = computed(() => ({
  q: searchMajorString.value,
}))

const { data: majorData, isLoading: isLoadingMajors } = useGetList<IMajor>({
  resource: 'major',
  page: queryMajorPage,
  per_page: queryMajorPerPage,
  filter: queryMajorFilter,
})

const majorItems = computed(() => {
  return majorData.value?.data.map((g) => ({
    title: g.title,
    value: g.id,
  }))
})
</script>

<template>
  <VForm
    @submit.prevent="onSubmit"
    @reset.prevent="onReset"
    class="create-course-form"
  >
    <VSheet class="pa-3">
      <v-text-field
        :label="t('nouns.title')"
        :error-messages="titleField.errorMessage.value"
        v-model="titleField.value.value"
      />
      <v-textarea
        prepend-icon="mdi-file-document-outline"
        :label="t('nouns.description')"
        :error-messages="descriptionField.errorMessage.value"
        v-model="descriptionField.value.value"
      />

      <VAutocomplete
        @update:search="searchMajorString = $event"
        :label="t('nouns.major')"
        :items="majorItems"
        v-model="majorField.value.value"
        :loading="isLoadingMajors"
        :error-messages="majorField.errorMessage.value"
        prepend-icon="mdi-book-multiple"
      />

      <CreateFormActionsToolbar :is-edit="isEdit" />
    </VSheet>
  </VForm>
</template>
<style scoped>
.create-course-form {
  max-width: 800px;
  margin: auto;
}
</style>

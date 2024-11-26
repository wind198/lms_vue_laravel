<script setup lang="ts">
import useClassInputs from '@/composables/useClassInputs'
import useGetList from '@/composables/useGetList.js'
import { ICourse } from '@/types/entities/course.entity.js'
import { IMajor } from '@/types/entities/major.entity.js'
import { removeTrailingSlash } from '@/utils/helpers.js'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

const { path, params } = useRoute()

const isEdit = computed(() => removeTrailingSlash(path).endsWith('update'))

const { courseField, onSubmit, onReset, descriptionField, titleField } =
  useClassInputs()

const queryCoursePage = ref(1)
const queryCoursePerPage = ref(1e5)

const searchCourseString = ref('')

const queryCourseFilter = computed(() => ({
  q: searchCourseString.value,
}))

const { data: courseData, isLoading: isLoadingCourses } = useGetList<ICourse>({
  resource: 'major',
  page: queryCoursePage,
  per_page: queryCoursePerPage,
  filter: queryCourseFilter,
})

const courseItems = computed(() => {
  return courseData.value?.data.map((g) => ({
    title: g.title,
    value: g.id,
  }))
})
</script>

<template>
  <VForm
    @submit.prevent="onSubmit"
    @reset.prevent="onReset"
    class="create-class-form"
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
        @update:search="searchCourseString = $event"
        :label="t('nouns.course')"
        :items="courseItems"
        v-model="courseField.value.value"
        :loading="isLoadingCourses"
        :error-messages="courseField.errorMessage.value"
        prepend-icon="mdi-book-multiple"
      />

      <CreateFormActionsToolbar :is-edit="isEdit" />
    </VSheet>
  </VForm>
</template>
<style scoped>
.create-class-form {
  max-width: 800px;
  margin: auto;
}
</style>

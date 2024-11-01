<script setup lang="ts">
import useQueryParamsStore, { ISearchParamKey } from '@/stores/query.js'
import useStudents from '../../composables/useStudents.js'
import { IOrder } from '../../types/common.type.js'
import {
  DEFAULT_ORDER,
  DEFAULT_ORDER_BY,
  DEFAULT_PAGE,
  DEFAULT_PER_PAGE,
  PAGINATION_SEARCH_PARAMS,
} from '../../utils/constants.js'
import useUpdateSearchParamsAndReload from '@/composables/useUpdateSearchParamsAndReload.js'
import useServerTableEventHandler from '@/composables/useServerTableEventHandler.js'
import useServerTablePaginationParams from '@/composables/useServerTablePaginationParams.js'
import { useI18n } from 'vue-i18n'

definePage({
  meta: {
    label: 'nouns.student',
    searchParams: PAGINATION_SEARCH_PARAMS,
  },
})

const { t } = useI18n()

const { searchParams } = useQueryParamsStore()

const { handleUpdateSort } = useServerTableEventHandler()

const { order, order_by, page, per_page } = useServerTablePaginationParams()

const { data, isLoading } = useStudents({
  order,
  order_by,
  page,
  per_page,
})

const studentList = computed(() => data.value?.data ?? [])

const paginationParams = computed(() => data.value?.params)

const headers = ref([
  { sortable: true, title: 'ID', value: 'id' },
  { sortable: true, title: t('nouns.fullName'), value: 'full_name' },
  { sortable: true, title: t('nouns.age'), value: 'age' },
  { sortable: true, title: t('nouns.gender'), value: 'gender' },
  { sortable: true, title: t('nouns.email'), value: 'email' },
  { sortable: true, title: t('nouns.phone'), value: 'phone' },
  { sortable: true, title: t('nouns.address'), value: 'address' },
  { sortable: true, title: t('nouns.createdAt'), value: 'created_at' },
  { sortable: true, title: t('nouns.actions'), value: 'actions' },
])
</script>
<template>
  <div class="data-list">
    <TableSkeleton v-if="isLoading" />
    <VDataTable
      :headers="headers"
      :items="studentList"
      :sort-by="[{ key: order_by, order }]"
      :hide-default-footer="true"
      :items-per-page="per_page"
      @update:sort-by="handleUpdateSort"
    />
    <ServerTablePagination
      :page="page"
      :per-page="per_page"
      :total-items="paginationParams?.total ?? 0"
    />
  </div>
</template>
<style scoped></style>

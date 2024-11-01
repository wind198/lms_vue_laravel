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
import useUpdateSearchParamsAndNavigate from '@/composables/useUpdateSearchParamsAndNavigate.js'
import useServerTableEventHandler from '@/composables/useServerTableEventHandler.js'
import useServerTablePaginationParams from '@/composables/useServerTablePaginationParams.js'
import { useI18n } from 'vue-i18n'
import useFormatDateTime from '../../composables/useFormatDateTime.js'
import { getAge, joinStr } from '../../utils/helpers.js'
import { storeToRefs } from 'pinia'

definePage({
  meta: {
    label: 'nouns.student',
    searchParams: PAGINATION_SEARCH_PARAMS,
  },
})

const { t } = useI18n()

const { searchParams } = storeToRefs(useQueryParamsStore())

const { handleUpdateSort, handleUpdatePage, handleUpdatePerPage } =
  useServerTableEventHandler()

const { order, order_by, page, per_page } = useServerTablePaginationParams()

const { data, isLoading, isFetching } = useStudents({
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
  { sortable: true, title: t('nouns.age'), value: 'dob' },
  { sortable: true, title: t('nouns.gender'), value: 'gender' },
  { sortable: true, title: t('nouns.email'), value: 'email' },
  { sortable: true, title: t('nouns.phone'), value: 'phone' },
  { sortable: true, title: t('nouns.address'), value: 'address' },
  { sortable: true, title: t('nouns.createdAt'), value: 'created_at' },
  { sortable: true, title: t('nouns.actions'), value: 'actions' },
])

const { formatDateCommon } = useFormatDateTime()
</script>
<template>
  <div class="data-list">
    <TableSkeleton v-if="isLoading" />
    <template v-else>
      <div class="w-full align-baseline d-flex flex-wrap ga-3">
        <v-spacer />
        <DateFilter
          :value="searchParams.filter.created_at?.gte"
          :label="t('others.createdAfter')"
          filter-key="created_at.gte"
        />
        <SearchBox :value="searchParams.filter.q" />
      </div>
      <VDataTable
        :headers="headers"
        :items="studentList"
        :sort-by="[{ key: searchParams.order_by, order: searchParams.order }]"
        :hide-default-footer="true"
        :items-per-page="searchParams.per_page"
        @update:sort-by="handleUpdateSort"
      >
        <template #item.created_at="{ value }">
          {{ formatDateCommon(value) }}
        </template>
        <template #item.dob="{ value }">
          {{ getAge(value) }}
        </template>
        <template #item.gender="{ value }">
          {{ t('nouns.' + value.toLowerCase()) }}
        </template>
        <template #item.email="{ value }">
          <a :href="`mailto:${value}`">
            {{ value }}
          </a>
        </template>
        <template #item.phone="{ value }">
          <a :href="`tel:${value}`">
            {{ value }}
          </a>
        </template>
        <template #item.address="{ value, item }">
          <LongTextWithElipsis
            :text="value"
            :title="
              t('others.smtOfsmo', {
                smt: t('nouns.address'),
                smo: joinStr(t('nouns.student').toLowerCase(), item.full_name),
              })
            "
          />
        </template>
      </VDataTable>
    </template>
    <ServerTablePagination
      @update-page="handleUpdatePage"
      @update-per-page="handleUpdatePerPage"
      :page="page"
      :per-page="per_page"
      :total-items="paginationParams?.total ?? 0"
    />
  </div>
</template>
<style scoped></style>

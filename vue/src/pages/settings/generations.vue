<script setup lang="ts">
import useQueryParamsStore, { ISearchParamKey } from '@/stores/query.js'
import {
  DEFAULT_ORDER,
  DEFAULT_ORDER_BY,
  DEFAULT_PAGE,
  DEFAULT_PER_PAGE,
  GENDER_LIST,
  PAGINATION_SEARCH_PARAMS,
} from '@/utils/constants.js'
import useServerTableEventHandler from '@/composables/useServerTableEventHandler.js'
import useServerTablePaginationParams from '@/composables/useServerTablePaginationParams.js'
import { useI18n } from 'vue-i18n'
import { storeToRefs } from 'pinia'
import useUsers from '@/composables/useUsers.js'
import useFormatDateTime from '@/composables/useFormatDateTime'
import { getAge, getDeleteApi, joinStr } from '@/utils/helpers'
import { IUser, IUserType } from '@/types/entities/user.entity'
import { useCommonStuffStore } from '@/stores/common'
import useSelection from '@/composables/useSelection'
import TableMetaToolbar from '@/components/common/TableMetaToolbar.vue'
import useGenerations from '@/composables/useGenerations'

const { t } = useI18n()

const { filterParams } = storeToRefs(useQueryParamsStore())

const { handleUpdateSort, handleUpdatePage, handleUpdatePerPage } =
  useServerTableEventHandler()

const { order, order_by, page, per_page } = useServerTablePaginationParams()

const { data, isLoading } = useGenerations({
  order,
  order_by,
  page,
  per_page,
  filter: filterParams,
})

const generationList = computed(() => data.value?.data ?? [])

const paginationParams = computed(() => data.value?.params)

const headers = ref([
  { sortable: true, title: t('nouns.title'), value: 'title' },
  { sortable: true, title: t('nouns.description'), value: 'description' },
  { sortable: true, title: t('nouns.startYear'), value: 'year' },
  { sortable: true, title: t('nouns.createdAt'), value: 'created_at' },
  { sortable: true, title: t('nouns.actions'), value: 'actions', width: 80 },
])

const { formatDateCommon } = useFormatDateTime()

const { onClickDeleteBulk, selectedEntitesText, selectedEntities } =
  useSelection({ entity: 'generation' })
</script>
<template>
  <div class="user-list px-3">
    <TableSkeleton v-if="isLoading" />
    <template v-else>
      <VToolbar class="d-flex align-center bg-transparent">
        <TableMetaToolbar
          :selected="selectedEntities.length"
          :selected-text="selectedEntitesText"
          :total="paginationParams?.total ?? 0"
          @clear="selectedEntities = []"
        />
        <v-spacer />
        <FilterToolbar v-if="!selectedEntities.length" />
        <BulkActionToolbar @delete="onClickDeleteBulk" v-else />
        <v-btn color="primary" variant="flat" to="/settings/generations/create">
          {{ t('actions.create') }}
        </v-btn>
      </VToolbar>
      <VDataTable
        v-model="selectedEntities"
        show-select
        :headers="headers"
        :items="generationList"
        :sort-by="[{ key: order_by, order: order }]"
        :hide-default-footer="true"
        :items-per-page="per_page"
        @update:sort-by="handleUpdateSort"
      >
        <template #item.actions="{ item }">
          <RowActionButtons
            :edit-url="`/settings/generations/${item.id}/update`"
            :representation="item.title"
            resource="generations"
            :id="item.id"
          />
        </template>

        <template #item.created_at="{ value }">
          {{ formatDateCommon(value) }}
        </template>
        <template #item.description="{ value, item }">
          <LongTextWithElipsis
            :text="value"
            :title="
              t('others.smtOfsmo', {
                smt: t('nouns.description'),
                smo: joinStr(t('nouns.generation').toLowerCase(), item.title),
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

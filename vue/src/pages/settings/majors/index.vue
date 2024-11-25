<script setup lang="ts">
import TableMetaToolbar from '@/components/common/TableMetaToolbar.vue'
import useFormatDateTime from '@/composables/useFormatDateTime'
import useGetList from '@/composables/useGetList'
import useSelection from '@/composables/useSelection'
import useServerTableEventHandler from '@/composables/useServerTableEventHandler.js'
import useServerTablePaginationParams from '@/composables/useServerTablePaginationParams.js'
import useQueryParamsStore from '@/stores/query.js'
import { IMajor } from '@/types/entities/major.entity'
import { joinStr } from '@/utils/helpers'
import { cloneDeep } from 'lodash-es'
import { storeToRefs } from 'pinia'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

const { filterParams } = storeToRefs(useQueryParamsStore())

const { handleUpdateSort, handleUpdatePage, handleUpdatePerPage } =
  useServerTableEventHandler()

const { order, order_by, page, per_page } = useServerTablePaginationParams()

const { data, isLoading } = useGetList<IMajor>({
  order,
  order_by,
  page,
  per_page,
  filter: filterParams,
  resource: 'major',
})

const majorList = computed(() => data.value?.data ?? [])

const paginationParams = computed(() => data.value?.params)

const headers = ref([
  { sortable: true, title: t('nouns.title'), value: 'title' },
  { sortable: true, title: t('nouns.description'), value: 'description' },
  { sortable: true, title: t('nouns.createdAt'), value: 'created_at' },
  { sortable: true, title: t('nouns.actions'), value: 'actions', width: 80 },
])

const { formatDateCommon } = useFormatDateTime()

const { onClickDeleteBulk, selectedEntitesText, selectedEntities } =
  useSelection({ resource: 'major' })

const router = useRouter()

const onRowClick = (_: any, itemWrapper: any) => {
  if (!itemWrapper.item) {
    return
  }
  router.push({
    path: `/settings/majors/` + itemWrapper.item.id,
    state: { userData: cloneDeep(itemWrapper.item) },
  })
}
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
        <v-btn color="primary" variant="flat" to="/settings/majors/create">
          {{ t('actions.create') }}
        </v-btn>
      </VToolbar>
      <VDataTable
        v-model="selectedEntities"
        show-select
        :headers="headers"
        :items="majorList"
        :sort-by="[{ key: order_by, order: order }]"
        :hide-default-footer="true"
        :items-per-page="per_page"
        @update:sort-by="handleUpdateSort"
        @click:row="onRowClick"
      >
        <template #item.actions="{ item }">
          <RowActionButtons
            @click.stop=""
            :edit-url="`/settings/majors/${item.id}/update`"
            :representation="item.title"
            resource="majors"
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
                smo: joinStr(t('nouns.major').toLowerCase(), item.title),
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

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

const props = defineProps<{ user_type: IUserType }>()

const { t } = useI18n()

const { filterParams } = storeToRefs(useQueryParamsStore())

const { handleUpdateSort, handleUpdatePage, handleUpdatePerPage } =
  useServerTableEventHandler()

const { order, order_by, page, per_page } = useServerTablePaginationParams()

const { data, isLoading } = useUsers({
  order,
  order_by,
  page,
  per_page,
  filter: filterParams,
  user_type: props.user_type,
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

const selectedUsers = ref<number[]>([])

const selectedUsersText = computed(() =>
  t('messages.info.selected', {
    count: selectedUsers.value.length,
    entity: t('nouns.' + 'student'),
  })
)

const { openDeleteBulkDialog } = useCommonStuffStore()

const resource = computed(() => props.user_type + 's')

const onClickDeleteBulk = () => {
  openDeleteBulkDialog({
    deleteApi: getDeleteApi(resource.value),
    title: joinStr(t('actions.delete'), t('nouns.' + props.user_type)),
    text: t('messages.confirmation.delete', {
      count: selectedUsers.value.length,
      entity: t('nouns.' + props.user_type),
    }),
    ids: selectedUsers.value,
    queryKey: [resource.value],
  })
}
</script>
<template>
  <div class="user-list px-3">
    <TableSkeleton v-if="isLoading" />
    <template v-else>
      <VToolbar class="d-flex align-center bg-transparent">
        <p class="pl-1 text-subtitle-2">
          <span v-if="!selectedUsers.length">
            {{ t('nouns.total') }}: {{ paginationParams?.total }}
          </span>
          <span v-else>
            {{ t('nouns.total') }}: {{ paginationParams?.total }} /
            {{ selectedUsersText }}
            <v-btn
              icon
              density="compact"
              variant="flat"
              size="small"
              @click="selectedUsers = []"
              class="remove-selection-btn"
            >
              <v-icon>mdi-close</v-icon>
            </v-btn>
          </span>
        </p>
        <v-spacer />
        <FilterToolbar v-if="!selectedUsers.length">
          <template #inner-append="{ setRef, visibilityMap }">
            <GenderFilter
              :ref="setRef"
              :label="t('nouns.gender')"
              filter-key="gender"
              default-value="FEMALE"
              :is-visible="!!visibilityMap.gender"
            />
          </template>
          <!-- <template v-slot:inner-append="{ setRef, visibilityMap }"/> -->
        </FilterToolbar>
        <BulkActionToolbar @delete="onClickDeleteBulk" v-else />
        <v-btn color="primary" variant="flat" to="/settings/students/create">
          {{ t('actions.create') }}
        </v-btn>
      </VToolbar>
      <VDataTable
        v-model="selectedUsers"
        show-select
        :headers="headers"
        :items="studentList"
        :sort-by="[{ key: order_by, order: order }]"
        :hide-default-footer="true"
        :items-per-page="per_page"
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
<style scoped>
.remove-selection-btn {
  vertical-align: text-bottom;
  position: relative;
  transform: translateY(1px);
}
</style>

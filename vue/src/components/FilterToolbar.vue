<script setup lang="ts">
import useQueryParamsStore from '@/stores/query'
import { get } from 'lodash-es'
import { storeToRefs } from 'pinia'
import DateFilter from './common/DateFilter.vue'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

const visibilityStatusList = ref<
  {
    filterKey: string
    isVisible: boolean
    label: string
    alwaysOn?: boolean
  }[]
>([])

const shouldHideFilterToggleButton = computed(() =>
  visibilityStatusList.value?.every((i) => i.alwaysOn)
)

const visibilityMap = computed(() =>
  Object.fromEntries(
    visibilityStatusList.value.map(({ filterKey, isVisible: value }) => [
      filterKey,
      value,
    ])
  )
)

const { filterParams } = storeToRefs(useQueryParamsStore())

const setRef = (el: any) => {
  if (!el) {
    return
  }
  const key: string = el.filterKey ?? el.attributes?.filterKey?.value
  const label = el.label ?? el.attributes?.label?.value ?? key
  const alwaysOn = !!(el.alwaysOn ?? el.attributes?.alwaysOn?.value)
  // const defaultValue = !!(el.defaultValue ?? el.attributes?.defaultValue?.value)
  if (!key) return
  if (visibilityStatusList.value?.find((i) => i.filterKey === key)) {
    return
  }
  const hasCurrentValue = get(filterParams.value, key.split('.')) !== undefined

  const shouldBeVisible = !!(alwaysOn || hasCurrentValue)
  visibilityStatusList.value.push({
    filterKey: key,
    isVisible: shouldBeVisible,
    label,
    alwaysOn,
  })
}

const toggleFilter = (keyStr: string) => {
  const newVisibilityStatusList = visibilityStatusList.value.map((i) => {
    if (i.filterKey !== keyStr) {
      return i
    }
    return {
      ...i,
      isVisible: !i.isVisible,
    }
  })

  visibilityStatusList.value = newVisibilityStatusList
}
</script>

<template>
  <div class="table-toolbar d-flex justify-end align-center pa-2">
    <slot name="inner-prepend" />

    <slot>
      <!-- Table search field -->
      <SearchBox always-on filter-key="q" :ref="setRef" />
      <!-- Date selector for "created_at.gte" -->
      <DateFilter
        :ref="setRef"
        class="created-after-filter"
        :label="t('others.createdAfter')"
        v-show="visibilityMap['created_at.gte']"
        filter-key="created_at.gte"
      />
      <!-- Date selector for "created_at.lte" -->
      <DateFilter
        :ref="setRef"
        class="created-before-filter"
        :label="t('others.createdBefore')"
        v-show="visibilityMap['created_at.lte']"
        filter-key="created_at.lte"
      />
    </slot>
    <slot
      :set-ref="setRef"
      :visibility-map="visibilityMap"
      name="inner-append"
    />

    <!-- VMenu to show/hide additional filters -->
    <VMenu :close-on-content-click="false" v-if="!shouldHideFilterToggleButton">
      <template #activator="{ props: activatorProps }">
        <VBtn icon variant="text" v-bind="activatorProps">
          <VIcon>mdi-filter-settings-outline</VIcon>
        </VBtn>
      </template>
      <VSheet class="filter-menu-sheet">
        <div class="d-flex flex-column pr-3 pl-4 py-2">
          <p class="text-subtitle-1">
            {{ t('nouns.filter') }}
          </p>
          <VSwitch
            density="compact"
            flat
            v-for="{
              filterKey,
              label,
              isVisible: value,
            } in visibilityStatusList.filter((i) => !i.alwaysOn)"
            :key="filterKey"
            :filter-key="filterKey"
            :model-value="value"
            :label="label"
            @update:model-value="toggleFilter(filterKey)"
            hide-details="auto"
          />
        </div>
      </VSheet>
    </VMenu>
  </div>
</template>

<style lang="scss" scoped>
.table-toolbar > *:not(style):not(:last-child) {
  margin-right: 8px;
}
.date-selector {
  width: 200px;
}
.filter-menu-sheet {
  min-width: 240px;
}
</style>

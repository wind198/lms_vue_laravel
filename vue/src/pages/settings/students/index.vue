<script setup lang="ts">
import useStudents from '../../../composables/useStudents.js'
import { IOrder } from '../../../types/common.type.js'
import {
  DEFAULT_ORDER,
  DEFAULT_ORDER_BY,
  DEFAULT_PAGE,
  DEFAULT_PER_PAGE,
} from '../../../utils/constants.js'

const { query } = useRoute()

const page: number = (query as Record<string, any>).page || DEFAULT_PAGE
const per_page: number =
  (query as Record<string, any>).per_page || DEFAULT_PER_PAGE
const order: IOrder = (query as Record<string, any>).order || DEFAULT_ORDER
const order_by: string =
  (query as Record<string, any>).order_by || DEFAULT_ORDER_BY

const { data, isLoading } = useStudents({ order, order_by, page, per_page })
</script>
<template>
  <div class="data-list">
    <TableSkeleton v-if="isLoading" />
    <v-data-table
      :items="data"
      :sort-by="[{ key: order_by, order }]"
    >
</v-data-table>
  </div>
</template>
<style scoped></style>

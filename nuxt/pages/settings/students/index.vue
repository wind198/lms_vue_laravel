<script setup lang="ts">
import type { IStudent } from "~/types/entities/student.entity";

const { query = {} } = useRoute();

const { data, error } = await useApiFetch<IStudent>("students", {
  key: "students",
  pick: [
    "full_name",
    "email",
    "email_verified_at",
    "gender",
    "dob",
    "education_background",
    "address",
  ],
  params: {
    page: query.page,
    per_page: query.per_page,
    sort_by: query.sort_by,
    filter: query.filter,
  },
});
</script>
<template>
  <div>hello students</div>
</template>
<style scoped></style>

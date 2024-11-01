<script setup lang="ts">
import { is } from '@babel/types'
import { useI18n } from 'vue-i18n'

const { matched } = useRoute()

const { t } = useI18n()

const breadcrumbs = computed(() =>
  [{ to: '/', title: t('nouns.home') }].concat(
    ...matched
      .filter((i) => !i.meta.isLayout && !i.redirect)
      .map((i) => {
        const { meta, path } = i
        const label = meta.label ? t(meta.label as string) : undefined

        const lastSegment = path.split('/').filter(Boolean).pop() ?? '/'
        return { to: path, title: label ?? lastSegment }
      })
  )
)
</script>
<template>
  <VBreadcrumbs :items="breadcrumbs" />
</template>
<style scoped></style>

<script setup lang="ts">
import { last } from 'lodash-es'
import { useI18n } from 'vue-i18n'

const { matched } = useRoute()

const { t } = useI18n()

const breadcrumbs = computed(() => {
  const output = [{ to: '/', title: t('nouns.home') }].concat(
    ...matched
      .filter((i) => !i.meta.isLayout && !i.redirect)
      .map((i) => {
        const { meta, path } = i
        let label: string | undefined
        if (meta.label) {
          if (Array.isArray(meta.label)) {
            // this is a i18n key with its params
            const [key, params] = meta.label
            label = t(
              key,
              Object.fromEntries(
                Object.entries(params).map(([k, v]) => [k, t(v).toLowerCase()])
              )
            )
          } else {
            // this is a simple i18n key
            label = t(meta.label as string)
          }
        }

        const lastSegment = path.split('/').filter(Boolean).pop() ?? '/'
        return { to: path, title: label ?? lastSegment }
      })
  )

  const lastItem = last(output)
  if (!lastItem) {
    return output
  }
  const lastItemLastSegment = last(lastItem.to.split('/').filter(Boolean))
  if (!lastItemLastSegment) {
    return output
  }
  if (['update', 'create'].includes(lastItemLastSegment)) {
    output.splice(output.length - 1, 0, {
      title: lastItem.title.replace(t(`actions.${lastItemLastSegment}`), ''),
      to: lastItem.to.split('/').slice(0, -1).join('/'),
    })
  }
  return output
})
</script>
<template>
  <VBreadcrumbs :items="breadcrumbs" />
</template>
<style scoped></style>

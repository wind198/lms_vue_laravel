<script setup lang="ts">
import useApiHttpClient from '@/composables/useHttpClient.js'
import { getPathSegments, getRepresentationUrl } from '@/utils/helpers.js'
import { get, last, set } from 'lodash-es'
import { useI18n } from 'vue-i18n'

const { matched, path: realPath } = toRefs(useRoute())

// const realPath = computed(() => route.path)
// const matched = computed(() => route.matched)

const realPathSegments = computed(() => {
  return getPathSegments(realPath.value)
})

const { t } = useI18n()

const lastMatch = computed(() => {
  return last(matched.value)
})

const { $get } = useApiHttpClient()

const representationMap = reactive<Record<string, Record<string, string>>>({})

watchEffect(async () => {
  if (!lastMatch.value) {
    return
  }

  const { path: matchPath } = lastMatch.value

  const pathSegments = getPathSegments(matchPath)

  const indexFields = pathSegments
    .map((i, index) => ({ value: i, index }))
    .filter((i) => i.value.startsWith(':'))

  const jobs = [] as {
    pathValue: string
    idValue: string
    resource: string
    index: number
    representation: Promise<string>
  }[]

  indexFields.forEach(({ index, value }) => {
    const resource = pathSegments[index - 1]

    const idValue = realPathSegments.value[index]

    if (!resource || !idValue) {
      return
    }

    jobs.push({
      index,
      pathValue: value,
      resource,
      idValue,
      representation: $get(getRepresentationUrl(resource, idValue)).then(
        (i) => i.data
      ),
    })
  })

  const representations = await Promise.all(jobs.map((i) => i.representation))

  representations.forEach((r, index) => {
    const { idValue, resource } = jobs[index]
    set(representationMap, [resource, idValue], r)
  })
})

const getPathForMatch = (matchPatch: string) => {
  const segments = getPathSegments(matchPatch)
  return '/' + realPathSegments.value.slice(0, segments.length).join('/')
}

const breadcrumbs = computed(() => {
  const output = [{ to: '/', title: t('nouns.home') }].concat(
    ...matched.value
      .filter((i) => !i.meta.isLayout && i.meta.isBreadcrumb)
      .map((i) => {
        const segments = getPathSegments(i.path)
        const lastSegment = realPathSegments.value[segments.length - 1]
        const resource = realPathSegments.value[segments.length - 2]
        const metaLabel = i.meta.label as string
        let label: string | undefined
        if (metaLabel) {
          if (Array.isArray(metaLabel)) {
            // this is a i18n key with its params
            const [key, params] = metaLabel
            label = t(
              key,
              Object.fromEntries(
                Object.entries(params).map(([k, v]) => [k, t(v).toLowerCase()])
              )
            )
          } else {
            // this is a simple i18n key
            label = t(metaLabel)
          }
        } else {
          if (i.meta.isIdRoute) {
            if (lastSegment && resource) {
              label = get(representationMap, [resource, lastSegment])
            }
          }
        }

        const actualPath = getPathForMatch(i.path)

        return { to: actualPath, title: label ?? lastSegment }
      })
  )

  return output.filter((i) => i.title)
})
</script>
<template>
  <VBreadcrumbs :items="breadcrumbs" />
</template>
<style scoped></style>

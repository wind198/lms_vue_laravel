import { computed } from 'vue'
import { createRouterMatcher, type RouteRecord, useRoute } from 'vue-router'

interface Breadcrumb {
  label: string;
  path: string;
}

export const useBreadcrumbs = () => {
  const route = useRoute()

  const router = useRouter()

  const allRoutes = router.getRoutes()

  console.log(allRoutes)

  const breadcrumbs = computed<Breadcrumb[]>(() => {
    const segments = route.path.split('/').filter(segment => segment) // Remove empty segments
    let accumulatedPath = ''

    return segments.map((segment, index) => {
      accumulatedPath += `/${segment}`
      return {
        label: segment.charAt(0).toUpperCase() + segment.slice(1), // Capitalize first letter
        path: accumulatedPath,
      }
    })
  })

  return {
    breadcrumbs,
  }
}

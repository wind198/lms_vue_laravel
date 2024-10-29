import { useRoute } from 'vue-router'

export const useActiveRoute = () => {
  const route = useRoute()

  const normalizePath = (path: string): string => {
    return path.endsWith('/') && path.length > 1 ? path.slice(0, -1) : path
  }

  const isActiveRoute = (url: string, exact: boolean = false): boolean => {
    const normalizedCurrentPath = normalizePath(route.path)
    const normalizedUrl = normalizePath(url)

    if (exact) {
      // Exact match with trailing slash handling
      return normalizedCurrentPath === normalizedUrl
    } else {
      // Partial match: Check if the URL is a prefix of the current path
      return normalizedCurrentPath.startsWith(normalizedUrl)
    }
  }

  return {
    isActiveRoute,
  }
}

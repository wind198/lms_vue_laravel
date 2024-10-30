import { NavigationGuardWithThis } from 'vue-router'
import useAuthStore from '../../stores/auth'
import useApiHttpClient from '../../composables/useHttpClient'
import { apiPrefix } from '../../utils/helpers'

export const authNavigationGuard: NavigationGuardWithThis<undefined> = async (
  to,
  from
) => {
  const { isAuthenticated, logout, user, login } = useAuthStore()

  const { $get } = useApiHttpClient()

  if (from.path === '/auth/login/' && isAuthenticated) {
    logout()
    return
  }
  if (to.path.startsWith('/auth')) {
    // allow auth routes (public)
    return true
  }
  if (!isAuthenticated) {
    if (!user) {
      return {
        path: '/auth/login',
        state: {
          from: from.fullPath,
        },
      }
    } else {
      const { data } = await $get(apiPrefix('user'))
      login({ user: data })
    }
  }
}

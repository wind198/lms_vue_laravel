import { NavigationGuardWithThis } from 'vue-router'
import authStore from '../../stores/auth'

export const authNavigationGuard: NavigationGuardWithThis<undefined> = (
  to,
  from
) => {
  const { isAuthenticated, logout } = authStore()

  if (from.path === '/auth/login/' && isAuthenticated) {
    logout()
    return
  }
  if (to.path.startsWith('/auth')) {
    // allow auth routes (public)
    return true
  }
  if (!isAuthenticated) {
    return {
      path: '/auth/login',
      state: {
        from: from.fullPath,
      },
    }
  }
}

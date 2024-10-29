import { defineStore } from 'pinia'
import { IUser } from '../types/entities/user.entity'
import { TOKEN_KEY, USER_KEY } from '../utils/constants'

type IAuthStore = {
  user: IUser | null
  token: string | null
}

const authStore = defineStore('auth', {
  state(): IAuthStore {
    const token = localStorage.getItem(TOKEN_KEY) || null
    const user = localStorage.getItem(USER_KEY) || null
    try {
      const userObject = user ? JSON.parse(user) : null
      return {
        token,
        user: userObject,
      }
    } catch (error) {
      console.error(error)
      return { token, user: null }
    }
  },
  actions: {
    login(payload: { user: IUser; token: string }) {
      this.user = payload.user
      this.token = payload.token
    },
    logout() {
      this.user = null
      this.token = null
      localStorage.removeItem(TOKEN_KEY)
      localStorage.removeItem(USER_KEY)
    },
  },
  getters: {
    isAuthenticated(s) {
      return !!s.token
    },
  },
})

export default authStore

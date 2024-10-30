import { defineStore } from 'pinia'
import { IUser } from '../types/entities/user.entity'
import { TOKEN_KEY, USER_KEY } from '../utils/constants'

type IAuthStore = {
  user: IUser | null
  isAuthenticated: boolean
}

const useAuthStore = defineStore('auth', {
  state(): IAuthStore {
    const user = localStorage.getItem(USER_KEY) || null
    try {
      const userObject = user ? JSON.parse(user) : null
      return {
        user: userObject,
        isAuthenticated: false,
      }
    } catch (error) {
      console.error(error)
      return { user: null, isAuthenticated: false }
    }
  },
  actions: {
    login(payload: { user: IUser }) {
      this.user = payload.user
      localStorage.setItem(USER_KEY, JSON.stringify(payload.user))
      this.isAuthenticated = true
    },
    logout() {
      this.user = null
      localStorage.removeItem(USER_KEY)
      this.isAuthenticated = false
    },
    setAuthenticate(v: boolean) {
      this.isAuthenticated = v
    },
  },
})

export default useAuthStore

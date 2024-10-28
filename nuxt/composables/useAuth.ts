import { ref, onBeforeMount } from "vue";
import { useRouter } from "#imports";
import type { IUser } from "../types/entities/user.entity";

type ILoginResponse = {
  token: string;
  user: IUser;
};

const USER_KEY = "USER_KEY";
const TOKEN_KEY = "TOKEN_KEY";
export const useAuth = () => {
  const user = ref<IUser | null>(null);
  const token = ref("");

  const isAuthenticated = ref(false);

  // Login function
  const login = async (email: string, password: string) => {
    const data = await $fetch<ILoginResponse>("auth/login", {
      method: "POST",
      body: { email, password },
    });

    const $token = data.token;
    const $user = data.user;
    token.value = $token;
    user.value = $user;
    isAuthenticated.value = true;

    localStorage.setItem(TOKEN_KEY, $token);
    localStorage.setItem(USER_KEY, JSON.stringify($user));
  };

  // Auto-login function on app load
  const initializeAuth = () => {
    const savedToken = localStorage.getItem(TOKEN_KEY);
    const savedUser = localStorage.getItem(USER_KEY);

    if (savedToken) {
      token.value = savedToken;
    }
    if (savedUser) {
      user.value = JSON.parse(savedUser);
    }
  };

  onBeforeMount(() => {
    initializeAuth();
  });

  // Logout function
  const logout = () => {
    localStorage.removeItem(TOKEN_KEY);
    localStorage.removeItem(USER_KEY);
    user.value = null;
    isAuthenticated.value = false;
  };

  return {
    login,
    logout,
    initializeAuth,
    user,
    token,
    isAuthenticated,
  };
};

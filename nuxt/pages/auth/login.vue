<script setup lang="ts">
import type { LayoutKey } from "../../.nuxt/types/layouts.js";
import { useAuth } from "../../composables/useAuth.js";

type IFormData = {
  email: string;
  password: string;
};

definePageMeta({
  layout: "login" as LayoutKey,
});

const { t } = useI18n();

const welcomeMsg = t("messages.info.welcome");
const subtitleMsg = t("messages.info.pleaseLogin");

const { isAuthenticated, login } = useAuth();

const formData = reactive<IFormData>({ email: "", password: "" });

const showingPasswd = ref(false);

const {} = useAsyncData(async () => {
  await login(formData.email, formData.password);
  router.push({ name: "dashboard" });
});

const router = useRouter();

onMounted(() => {
  if (isAuthenticated.value) {
    router.push({ name: "dashboard", replace: true });
  }
});
</script>
<template>
  <v-form class="login-form pa-3">
    <v-card>
      <v-card-title>{{ welcomeMsg }}</v-card-title>
      <v-card-subtitle>{{ subtitleMsg }}</v-card-subtitle>
      <v-card-text>
        <v-text-field
          type="email"
          prepend-inner-icon="mdi-email"
          :label="t('nouns.email')"
        >
        </v-text-field>
        <v-text-field
          :type="showingPasswd ? 'text' : 'password'"
          prepend-inner-icon="mdi-lock"
          :label="t('nouns.password')"
        >
        </v-text-field>
      </v-card-text>
      <v-card-actions>
        <v-btn block color="primary" variant="flat" :elevation="0">{{
          t("actions.login")
        }}</v-btn>
      </v-card-actions>
    </v-card>
  </v-form>
</template>
<style scoped>
.login-form {
  max-width: 800px;
}
</style>

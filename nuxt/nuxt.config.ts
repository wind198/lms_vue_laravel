import type { NuxtConfig } from "nuxt/schema";

// https://nuxt.com/docs/api/configuration/nuxt-config
const nuxtConfig: NuxtConfig = {
  compatibilityDate: "2024-04-03",
  devtools: { enabled: true },
  runtimeConfig: {
    public: {
      apiUrl: "",
    },
  },
  modules: ["vuetify-nuxt-module", "@nuxtjs/i18n"],
  i18n: {
    vueI18n: "./i18n.config.ts", // if you are using custom path, default
  },
  vuetify: {
    vuetifyOptions: {
      theme: {
        defaultTheme: "light",
        themes: {
          light: {
            dark: false,
            colors: {},
          },
        },
      },
    },
  },
};

export default defineNuxtConfig(nuxtConfig);

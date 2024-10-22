// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: "2024-04-03",
  devtools: { enabled: true },
  runtimeConfig: {
    public: { BASE_URL: "/api" }, // Your API base prefix
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
            colors: {
              primary: "#8BC34A",
              secondary: "#FFEB3B",
            },
          },
        },
      },
    },
  },
});

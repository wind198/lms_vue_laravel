/**
 * plugins/index.ts
 *
 * Automatically included in `./src/main.ts`
 */

// Plugins
import vuetify from './vuetify'
import pinia from '../stores'
import router from '../router'

// Types
import type { App } from 'vue'
import i18n from '../lang/i18n'
import { QueryClient, VueQueryPlugin } from '@tanstack/vue-query'
import { DataLoaderPlugin } from 'unplugin-vue-router/data-loaders'

export function registerPlugins(app: App) {
  const queryClient = new QueryClient({
    defaultOptions: {
      queries: {
        retry: false,
      },
    },
  })
  app
    .use(vuetify)
    .use(i18n)
    .use(VueQueryPlugin, { queryClient })
    .use(DataLoaderPlugin, { router })
    .use(router)
    .use(pinia)
}

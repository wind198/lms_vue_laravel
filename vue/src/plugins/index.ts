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
import { VueQueryPlugin } from '@tanstack/vue-query'
import { DataLoaderPlugin } from 'unplugin-vue-router/data-loaders'

export function registerPlugins(app: App) {
  app
    .use(vuetify)
    .use(pinia)
    .use(i18n)
    .use(VueQueryPlugin)
    .use(DataLoaderPlugin, { router })
    .use(router)
}

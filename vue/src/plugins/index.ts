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

export function registerPlugins(app: App) {
  app.use(vuetify).use(router).use(pinia).use(i18n).use(VueQueryPlugin)
}

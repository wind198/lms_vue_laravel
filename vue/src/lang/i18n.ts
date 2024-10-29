import { createI18n } from 'vue-i18n'
import { vi } from './vi'

export type ITranslationGroup = Record<string, string>

export type ITranslation = {
  nouns: ITranslationGroup
  actions: ITranslationGroup
  messages: { info: ITranslationGroup; error: ITranslationGroup }
  others: ITranslationGroup
}

const i18n = createI18n({
  locale: 'vi',
  messages: {
    vi,
  },
})

export default i18n

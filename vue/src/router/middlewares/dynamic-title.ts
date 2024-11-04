import { useI18n } from 'vue-i18n'
import { NavigationGuardWithThis } from 'vue-router'
import i18n from '../../lang/i18n'

export const DynamicTitleMiddleware: NavigationGuardWithThis<
  undefined
> = async (to) => {
  const { meta } = to
  if (!meta.title) {
    return
  }
  const title = meta.title as string

  let translatedTitle

  const t = i18n.global.t

  if (Array.isArray(title)) {
    // this is a i18n key with its params
    const [key, params] = title
    translatedTitle = t(
      key,
      Object.fromEntries(
        Object.entries(params).map(([k, v]) => [k, t(v).toLowerCase()])
      )
    )
  } else {
    // this is a simple i18n key
    translatedTitle = t(title as string)
  }

  window.document.title = translatedTitle
}

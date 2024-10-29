import { useI18n } from 'vue-i18n'

export default function useVuetifyValidationRules() {
  const { t } = useI18n()

  // Required validation
  const required =
    (msg: string = t('messages.validations.required')) =>
    (v: any) => {
      if (!v && v !== 0) return msg
      if (typeof v === 'string' && !v.trim()) return msg
      return true
    }

  // Email validation
  const email =
    (msg: string = t('messages.validations.invalidEmail')) =>
    (v: string) => {
      const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
      return emailPattern.test(v) || msg
    }

  // Minimum length validation
  const minLength =
    (
      min: number,
      msg: string = t('messages.validations.minLength', {
        min,
      })
    ) =>
    (v: string) => {
      return (v && v.length >= min) || msg
    }

  // Maximum length validation
  const maxLength =
    (
      max: number,
      msg: string = t('messages.validations.maxLength', {
        max,
      })
    ) =>
    (v: string) => {
      return (v && v.length <= max) || msg
    }

  // Numeric validation
  const numeric =
    (msg: string = t('messages.validations.numeric')) =>
    (v: any) => {
      return !isNaN(v) || msg
    }

  // Phone validation (optional example)
  const phone =
    (msg: string = t('messages.validations.invalidPhone')) =>
    (v: string) => {
      const phonePattern = /^[0-9]{10,15}$/ // Adjust for phone format (example for 10-15 digits)
      return phonePattern.test(v) || msg
    }

  return { required, email, minLength, maxLength, numeric, phone }
}

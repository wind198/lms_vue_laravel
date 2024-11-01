import dayjs, { ConfigType } from 'dayjs'

export default function useFormatDateTime() {
  const formatDateCommon = (v: ConfigType) => {
    return dayjs(v).format('DD/MM/YYYY')
  }
  const formatDateTimeCommon = (v: ConfigType) => {
    return dayjs(v).format('DD/MM/YYYY HH:mm')
  }

  return { formatDateCommon, formatDateTimeCommon }
}

import { useI18n } from 'vue-i18n'
import { useCommonStuffStore } from '../stores/common'
import { getDeleteApi, joinStr } from '../utils/helpers'

type IOptions = {
  entity: string
  entityPlural?: string
}

export default function useSelection(options: IOptions) {
  const { entity, entityPlural = entity + 's' } = options

  const { t } = useI18n()

  const selectedEntities = ref<number[]>([])

  const { openDeleteBulkDialog } = useCommonStuffStore()

  const selectedEntitesText = computed(() =>
    t('messages.info.selected', {
      count: selectedEntities.value.length,
      entity: t('nouns.' + entity),
    })
  )

  const onClickDeleteBulk = () => {
    openDeleteBulkDialog({
      deleteApi: getDeleteApi(entityPlural),
      title: joinStr(t('actions.delete'), t('nouns.' + entity)),
      text: t('messages.confirmation.delete', {
        count: selectedEntities.value.length,
        entity: t('nouns.' + entity),
      }),
      ids: selectedEntities.value,
      queryKey: [entity],
    })
  }

  return { selectedEntities, selectedEntitesText, onClickDeleteBulk }
}

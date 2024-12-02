import { useI18n } from 'vue-i18n'
import { useCommonStuffStore } from '../stores/common'
import { getDeleteApi, joinStr } from '../utils/helpers'
import { IHasResource } from '@/types/common.type'

type IOptions = IHasResource

export default function useSelection(options: IOptions) {
  const { resource, resourcePlural = resource + 's' } = options

  const { t } = useI18n()

  const selectedEntities = ref<number[]>([])

  const { openDeleteBulkDialog } = useCommonStuffStore()

  const selectedEntitesText = computed(() =>
    t('messages.info.selected', {
      count: selectedEntities.value.length,
      entity: t('nouns.' + resource),
    })
  )

  const onClickDeleteBulk = () => {
    openDeleteBulkDialog({
      deleteApi: getDeleteApi(resourcePlural),
      title: joinStr(t('actions.delete'), t('nouns.' + resource)),
      text: t('messages.confirmation.delete', {
        count: selectedEntities.value.length,
        entity: t('nouns.' + resource),
      }),
      ids: selectedEntities.value,
      queryKey: [resource],
    })
  }

  return { selectedEntities, selectedEntitesText, onClickDeleteBulk }
}

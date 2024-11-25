import { IMajor } from '@/types/entities/major.entity'
import { IHasDescriptiveFields, IHasId, ITimeStamp } from '../common.type'

export type ICourseCoreField = {} & IHasDescriptiveFields & {
    courses_count?: number
    major?: IMajor[]
  }

export type ICourse = ICourseCoreField & IHasId & ITimeStamp

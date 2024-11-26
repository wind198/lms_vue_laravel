import { ICourse } from '@/types/entities/course.entity'
import { IHasDescriptiveFields, IHasId, ITimeStamp } from '../common.type'

export type IClassCoreField = {} & IHasDescriptiveFields & {
    course_id: string
    course?: ICourse
  }

export type IClass = IClassCoreField & IHasId & ITimeStamp

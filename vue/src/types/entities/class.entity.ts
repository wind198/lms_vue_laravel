import { ICourse } from '@/types/entities/course.entity'
import { IHasDescriptiveFields, IHasId, ITimeStamp } from '../common.type'
import { IClassSession } from '@/types/entities/class-session.entity'

export type IClassCoreField = {} & IHasDescriptiveFields & {
    course_id: string
    course?: ICourse
    sessions?: IClassSession[]
  }

export type IClass = IClassCoreField & IHasId & ITimeStamp

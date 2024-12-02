import { ICourse } from '@/types/entities/course.entity'
import { IHasDescriptiveFields, IHasId, ITimeStamp } from '../common.type'
import { IClass } from '@/types/entities/class.entity'
import { IRoom } from '@/types/entities/room.entity'

export type IClassSessionCoreField = {} & IHasDescriptiveFields & {
    class_id: string
    class?: IClass
    start_time: string
    end_time: string
    room_id: string
    room?: IRoom
  }

export type IClassSession = IClassSessionCoreField & IHasId & ITimeStamp

import { ITranslation } from '../i18n'

export const vi = {
  actions: {
    create: 'Tạo',
    update: 'Cập nhật',
    delete: 'Xóa',
    save: 'Lưu',
    cancel: 'Hủy',
    login: 'Đăng nhập',
    logout: 'Đăng xuất',
  },
  nouns: {
    email: 'Địa chỉ email',
    password: 'Mật khẩu',
    dob: 'Ngày sinh',
    phone: 'Số điện thoại',
    address: 'Địa chỉ',
    firstName: 'Tên',
    lastName: 'Họ',
    fullName: 'Họ và tên',
    age: 'Tuổi',
    course: 'Khóa học',
    lesson: 'Bài học',
    username: 'Tên người dùng',
    role: 'Vai trò',
    gender: 'Giới tính',
    confirmPassword: 'Xác nhận mật khẩu',
    profilePicture: 'Ảnh đại diện',
    studentId: 'Mã sinh viên',
    teacherId: 'Mã giáo viên',
    dateOfEnrollment: 'Ngày nhập học',
    educationLevel: 'Trình độ học vấn',
    institution: 'Cơ sở giáo dục',
    notes: 'Ghi chú',
    subject: 'Môn học',
    feedback: 'Phản hồi',
    assignment: 'Bài tập',
    deadline: 'Hạn nộp',
    student: 'Sinh viên',
    generation: 'Khóa sinh viên',
    teacher: 'Giáo viên',
    management: 'Quản lý',
    study: 'Học tập',
    home: 'Trang chủ',
    createdAt: 'Ngày tạo',
    actions: 'Hành động',
  },
  messages: {
    info: {
      welcome: 'Chào mừng đến với Hệ thống Quản lý Học tập',
      savedSuccessfully: 'Các thay đổi của bạn đã được lưu thành công.',
      deletedSuccessfully: 'Xóa thành công.',
      actionCompleted: 'Hành động đã được hoàn tất thành công.',
      pleaseLogin: 'Vui lòng đăng nhập để tiếp tục.',
    },
    error: {},
    validations: {
      pleaseLogin: 'Đăng nhập để tiếp tục',
      minLength: 'Trường này phải có ít nhất {min} ký tự.',
      maxLength: 'Trường này không được vượt quá {max} ký tự.',
      required: 'Trường này là bắt buộc.',
      invalidEmail: 'Vui lòng nhập một địa chỉ email hợp lệ.',
      numeric: 'Trường này chỉ chấp nhận số.',
      match: 'Trường này phải trùng khớp với {field}.',
      between: 'Trường này phải nằm trong khoảng từ {min} đến {max}.',
      maxValue: 'Giá trị không được lớn hơn {max}.',
      minValue: 'Giá trị không được nhỏ hơn {min}.',
      passwordComplexity:
        'Mật khẩu phải có ít nhất {minLength} ký tự, bao gồm chữ hoa, chữ thường, và số.',
      emailExists: 'Email đã tồn tại trong hệ thống.',
      dateInThePast: 'Ngày phải ở quá khứ.',
      dateInTheFuture: 'Ngày phải ở tương lai.',
      alpha: 'Trường này chỉ chấp nhận chữ cái.',
      alphaNum: 'Trường này chỉ chấp nhận chữ cái và số.',
      url: 'Vui lòng nhập một URL hợp lệ.',
      phone: 'Vui lòng nhập số điện thoại hợp lệ.',
    },
  },
  others: {
    appName: 'Hệ thống Quản lý Học tập',
    appNameShort: 'LMS',
    footerText: '© 2024 LMS. Bản quyền thuộc về LMS.',
  },
} as ITranslation
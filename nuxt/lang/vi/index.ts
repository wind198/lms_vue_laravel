import type { ITranslation } from "../../i18n.config";

export const vi = {
  actions: {
    create: "Tạo",
    update: "Cập nhật",
    delete: "Xóa",
    save: "Lưu",
    cancel: "Hủy",
    login: "Đăng nhập",
    logout: "Đăng xuất",
  },
  entities: {
    user: "Người dùng",
    student: "Học sinh",
    teacher: "Giáo viên",
    course: "Khóa học",
    lesson: "Bài học",
  },
  messages: {
    info: {
      welcome: "Chào mừng đến với Hệ thống Quản lý Học tập",
      savedSuccessfully: "Các thay đổi của bạn đã được lưu thành công.",
      deletedSuccessfully: "Xóa thành công.",
      actionCompleted: "Hành động đã được hoàn tất thành công.",
    },
    error: {},
    validations: {
      minLength: "This field must be at least {min} characters long.",
      maxLength: "This field must be no more than {max} characters long.",
      required: "This field is required.",
      invalidEmail: "Please enter a valid email address.",
    },
  },
  others: {
    appName: "Hệ thống Quản lý Học tập",
    appNameShort: "LMS",
    footerText: "© 2024 LMS. Bản quyền thuộc về LMS.",
  },
} as ITranslation;

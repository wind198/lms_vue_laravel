import type { ITranslation } from "../../i18n.config";

export const en = {
  actions: {
    create: "Create",
    update: "Update",
    delete: "Delete",
    save: "Save",
    cancel: "Cancel",
    login: "Login",
    logout: "Logout",
  },
  entities: {
    settings: "Settings",
    study: "Study",
    user: "User",
    student: "Student",
    teacher: "Teacher",
    course: "Course",
    lesson: "Lesson",
    generation: "Generation",
    major: "Major",
    class: "Class",
  },
  messages: {
    info: {
      welcome: "Welcome to the Learning Management System",
      savedSuccessfully: "Your changes have been saved successfully.",
      actionCompleted: "Action completed successfully.",
    },
    error: {
      requiredField: "This field is required.",
      invalidCredentials: "Invalid login credentials.",
      actionFailed: "Action failed. Please try again.",
    },
    validations: {
      minLength: "Trường này phải có ít nhất {min} ký tự.",
      maxLength: "Trường này không được vượt quá {max} ký tự.",
      required: "Trường này là bắt buộc.",
      invalidEmail: "Vui lòng nhập một địa chỉ email hợp lệ.",
    },
  },
  others: {
    appName: "Learning Management System",
    appNameShort: "LMS",
    footerText: "© 2024 LMS. All rights reserved.",
  },
} as ITranslation;

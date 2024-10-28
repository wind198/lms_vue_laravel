export const UserTypeList = ["student", "teacher", "admin"] as const;
export type IUserType = (typeof UserTypeList)[number];
export type IUser = {
  id: number;
  first_name: string;
  last_name: string;
  full_name: string;
  email: string;
  email_verified_at: Date | null; // Nullable timestamp
  user_type: "student" | "teacher" | "admin"; // Enum values for user_type
  phone?: string | null; // Nullable string
  address?: string | null; // Nullable string
  education_background: "high_school" | "bachelor" | "master" | "phd"; // Example values for education_background
  gender: "male" | "female" | "other"; // Example values for gender
  dob?: Date | null; // Nullable date
  created_at: Date;
  updated_at: Date;
};

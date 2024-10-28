import { uniqueId } from "lodash-es";
import { ref } from "vue";

type ToastOptions = {
  id: string;
  message: string;
  type?: "success" | "error" | "info";
  duration?: number;
};

export const useToast = () => {
  const toasts = ref<ToastOptions[]>([]);

  const show = (options: Omit<ToastOptions, "id">) => {
    const id = uniqueId();
    toasts.value.push({ ...options, id });

    // Auto-remove toast after specified duration
    setTimeout(() => {
      toasts.value = toasts.value.filter((i) => i.id !== id);
    }, options.duration || 3000);
  };

  return { show, toasts };
};

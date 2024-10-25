import type { UseFetchOptions } from "#app";

export const useApiFetch = async <T>(url: string, options: UseFetchOptions<T>) => {
  const config = useRuntimeConfig();
  return useFetch(`${config.public.apiUrl}/${url}`, options);
};

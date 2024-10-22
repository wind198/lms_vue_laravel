export const useApiFetch = async (url: string, options = {}) => {
  const config = useRuntimeConfig();
  return useFetch(`${config.public.BASE_URL}/${url}`, options);
};

import redaxios from 'redaxios'
const httpClient = redaxios.create({ baseURL: import.meta.env.VITE_API_URL })
export { httpClient }

import redaxios from 'redaxios'
const httpClient = redaxios.create({ baseURL: import.meta.env.BASE_URL })
export { httpClient }

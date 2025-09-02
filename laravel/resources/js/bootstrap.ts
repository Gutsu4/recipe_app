import axios, { AxiosInstance } from "axios";

declare global {
  interface Window {
    axios: AxiosInstance;
  }
}

window.axios = axios;

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

const api = axios.create({
  baseURL: import.meta.env.VITE_APP_URL + "/api",
  headers: {
    "X-Requested-With": "XMLHttpRequest",
  },
});

export default api;

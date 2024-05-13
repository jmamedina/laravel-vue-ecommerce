// Import necessary modules and components
// 必要なモジュールとコンポーネントをインポートする
import axios from "axios";
import store from "./store";
import router from "./router/index.js";

// Create an instance of axios with base URL
// ベースURLを指定してaxiosのインスタンスを作成する
const axiosClient = axios.create({
  baseURL: `http://localhost:10082/api`
})

// Add request interceptor to attach authorization token to each request
// 各リクエストに認証トークンを添付するリクエストインターセプターを追加する
axiosClient.interceptors.request.use(config => {
  config.headers.Authorization = `Bearer ${store.state.user.token}`
  return config;
})

// Add response interceptor to handle unauthorized responses
// 未認証の応答を処理するレスポンスインターセプターを追加する
axiosClient.interceptors.response.use(response => {
  return response;
}, error => {
  if (error.response.status === 401) {
    // Clear token and redirect to login page on 401 (Unauthorized) response
    // 401（未認証）の応答でトークンをクリアし、ログインページにリダイレクトする
    store.commit('setToken', null)
    router.push({ name: 'login' })
  }
  throw error;
})

export default axiosClient;

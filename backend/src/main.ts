import { createApp } from 'vue'
import store from './store'
import router from './router'
import './app.css'
import App from './App.vue'

const app = createApp(App).use(router).use(store)

app.mount('#app')

// createApp(App).mount('#app')
//     .use(router)
//     .use(store)
//     .mount('#app')

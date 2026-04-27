import { createApp } from 'vue'
import { createPinia } from 'pinia'
import { router } from '@/router/index.js'
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate'
import { plugin, defaultConfig } from '@formkit/vue'

import App from '@/App.vue'

import '@assets/main.css'

if (localStorage.getItem('darkMode') === 'true') {
  document.documentElement.classList.add('dark')
}

createApp(App)
  .use(createPinia().use(piniaPluginPersistedstate))
  .use(router)
  .use(plugin, defaultConfig)
  .mount('#app')
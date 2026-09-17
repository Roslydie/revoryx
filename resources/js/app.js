import './bootstrap';

import { createApp } from 'vue';
import router from './Components/routers/index.js';
import App from './Components/App.vue';
import i18n from './Components/plugins/i18n.js';

const app = createApp(App)

app.use(router)
app.use(i18n)
app.config.globalProperties._e = (key, fallback = key) => (
	i18n.global.te(key) ? i18n.global.t(key) : fallback
)

router.isReady().then(() => {
	app.mount('#app')
})

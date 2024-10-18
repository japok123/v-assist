import { createApp } from 'vue'
import App from './App.vue'
import router from './router'  
import 'bootstrap/dist/css/bootstrap.css'
import { registerPlugins } from '@/plugins'
import axios from './service/axios'
import Swal from 'sweetalert2'

const app = createApp(App)
app.config.globalProperties.$axios = axios
app.config.globalProperties.$swal = Swal
registerPlugins(app)
app.use(router)
app.mount('#app')

import './bootstrap'

import { createApp } from 'vue'
import UserDashboard from './components/UserDashboard.vue'

const app = createApp({})

app.component('user-dashboard', UserDashboard)

app.mount('#app')
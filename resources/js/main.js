import 'core-js/stable'
import Vue from 'vue'
import App from './App.vue'
import router from './router'
import CoreuiVue from '@coreui/vue'
import { iconsSet as icons } from '../assets/icons/icons.js'
import store from './store'

Vue.prototype.$apiAdress = 'http://localhost:80'
Vue.config.performance = true
Vue.use(CoreuiVue)

// window.Vue = require('vue').default;

new Vue({
  el: '#app',
  router,
  store,  
  icons,
  template: '<App/>',
  components: {
    App
  },
})

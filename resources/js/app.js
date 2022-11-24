require('./bootstrap');

window.Vue = require('vue').default;
import VueRouter from 'vue-router'
Vue.use(VueRouter)
import VueGoodTablePlugin from 'vue-good-table';
// import the styles
import 'vue-good-table/dist/vue-good-table.css'

Vue.use(VueGoodTablePlugin);

// v-select
import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css';
Vue.component('v-select', vSelect)
// date picker
import 'pc-bootstrap4-datetimepicker/build/css/bootstrap-datetimepicker.css';
import datePicker from 'vue-bootstrap-datetimepicker';
Vue.use(datePicker);

// Vue.component('home', require('./components/home.vue').default);
import routes from "./routes.js";
const router = new VueRouter({
    mode: "history",
    routes
  })

const app = new Vue({
    router,
    el: '#app',
});

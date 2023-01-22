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
import VueDatePicker from '@mathieustan/vue-datepicker';
import '@mathieustan/vue-datepicker/dist/vue-datepicker.min.css';

Vue.use(VueDatePicker);
//moment
import moment from 'moment';

// jquery use
import $ from "jquery"
window.$ = window.jQuery = require('jquery/dist/jquery');
// chart
import VueApexCharts from 'vue-apexcharts'
Vue.use(VueApexCharts)
Vue.component('apexchart', VueApexCharts)

// router
import routes from "./routes.js";
import Vue from 'vue';
const router = new VueRouter({
    mode: "history",
    routes
  })

const app = new Vue({
    router,
    el: '#app',
});

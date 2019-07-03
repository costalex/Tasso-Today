import Vue from 'vue';

import env from '@/env';
import store from './store';
import routes from '@/routes';
import VueRouter from 'vue-router';
import VueResource from 'vue-resource';
import vueScrollto from 'vue-scrollto';
import VueNotifications from 'vue-notification';
import VueSession from 'vue-session';

import Raven from 'raven-js';
import RavenVue from 'raven-js/plugins/vue';

import datePicker from 'vue-bootstrap-datetimepicker';
require('./bootstrap');

Vue.use(VueRouter);
Vue.use(VueResource);
Vue.use(vueScrollto);
Vue.use(VueNotifications);
Vue.use(VueSession, { persist: true });

Vue.use(datePicker);

Vue.config.productionTip = false;

const router = new VueRouter({
    routes,
    mode: 'history',
    fallback: false,
    props: true
});

Raven
    .config(env.SENTRY_DSN, {
        release: env.APP_VERSION
    })
    .addPlugin(RavenVue, Vue)
    .install();

router.afterEach((to, from) => {
    window.scrollTo(0, 0);
});

const app = new Vue({ // eslint-disable-line no-unused-vars
    store,
    router,
    el: '#body',
    render: h => h(require('./App.vue'))
});

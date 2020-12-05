require('./bootstrap');

import Vue from 'vue';
import Vuetify from 'vuetify/lib';
import {InertiaApp} from "@inertiajs/inertia-vue";

/**
 * Configure Vuetify
 */

const vuetifyOptions = {
    icons: {
        iconfont: 'mdi'
    }
};

Vue.use(Vuetify);


/**
 * Configure Inertia
 */

Vue.config.productionTip = false;
Vue.mixin({ methods: { route: (...args) => window.route(...args).url() } });
Vue.use(InertiaApp);


const root = document.getElementById('app');

new Vue({
    vuetify: new Vuetify(vuetifyOptions),
    render: h => h(InertiaApp, {
        props: {
            initialPage: JSON.parse(root.dataset.page),
            resolveComponent: name => require(`./Pages/${name}`).default
        }
    })
}).$mount(root);

import './bootstrap';
import { createApp } from 'vue';
import ToastPlugin from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-bootstrap.css';
import 'vue-select/dist/vue-select.css';

import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
import 'primeicons/primeicons.css'

import vSelect from 'vue-select'

const app = createApp({});
app.use(ToastPlugin);
app.use(VueSweetalert2);

import ModalComponent from './components/ModalComponent.vue';
import ProveedorComponent from './components/ProveedorComponent.vue';
import Valetcomponent from './components/ValetComponent.vue';

app.component('modal-component', ModalComponent);
app.component('proveedor-component', ProveedorComponent);
app.component('valet-component', Valetcomponent);
app.component('v-select', vSelect)

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// Object.entries(import.meta.glob('./**/*.vue', { eager: true })).forEach(([path, definition]) => {
//     app.component(path.split('/').pop().replace(/\.\w+$/, ''), definition.default);
// });

/**
 * Finally, we will attach the application instance to a HTML element with
 * an "id" attribute of "app". This element is included with the "auth"
 * scaffolding. Otherwise, you will need to add an element yourself.
 */

app.mount('#app');

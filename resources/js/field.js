import IndexField from './components/IndexField.vue';
import DetailField from './components/DetailField.vue';
import FormField from './components/FormField';

Nova.booting((Vue, router) => {
    Vue.component('index-fontawesome', IndexField);
    Vue.component('detail-fontawesome', DetailField);
    Vue.component('form-fontawesome', FormField);
});
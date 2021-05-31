import { createApp } from 'vue';
import store from "./store";
import router from "./router";
import App from './App.vue';

import AppCard from "./components/UI/AppCard";
import AppModal from "./components/UI/AppModal";
import AppButton from "./components/UI/AppButton";
import AppAutocomplete from "./components/UI/AppAutocomplete";
import AppLoader from "./components/UI/AppLoader";
import AppAlert from "./components/UI/AppAlert";

const app = createApp(App);

app.component('AppCard', AppCard);
app.component('AppModal', AppModal);
app.component('AppButton', AppButton);
app.component('AppAutocomplete', AppAutocomplete);
app.component('AppLoader', AppLoader);
app.component('AppAlert', AppAlert);

app.use(store);
app.use(router);
app.mount("#app");
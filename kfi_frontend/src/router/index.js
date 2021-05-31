import { createWebHistory, createRouter } from "vue-router";
import Home from "@/pages/Home.vue";
import Authors from "@/pages/Authors/index.vue";
import Products from "@/pages/Products/index.vue";
import NotFound from "@/pages/NotFound.vue";

const routes = [
    {
        path: "/",
        name: "Home",
        component: Home,
        meta: {
            hideNavbar: true
        }
    },
    {
        path: "/authors",
        name: "Authors",
        component: Authors,
    },
    {
        path: "/products",
        name: "Products",
        component: Products,
    },
    {
        path: "/:notFound(.*)",
        component: NotFound
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
    linkActiveClass: 'current'
});

export default router;
// SPA用ルータ
import VueRouter from "vue-router";
import HeaderComponent from "./components/HeaderComponent";
// メイン部分のコンポーネント
import TaskListComponent from "./components/TaskListComponent";

window.Vue = require("vue");

// Vueルータの定義
Vue.use(VueRouter);

const router = new VueRouter({
    mode: "history",
    routes: [
        {
            path: "/tasks",
            name: "task.list",
            component: TaskListComponent,
        },
    ],
});

const app = new Vue({
    el: "#app",
    router,
});

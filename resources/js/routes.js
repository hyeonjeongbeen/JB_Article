import Home from './pages/Home';
import Register from './pages/Register';
import Login from './pages/Login';
import Article from './views/Article';
import Vue from 'vue';

// router.beforeEach(function (to, from, next) {
//     var a = 1;
//     if(a==2){
//         return next();
//     }
//     else if(a==1){
//         next('/login');
//     }
//   });

export default [
    {
        path:'/',
        component: Home,
        name:'home'
    },
    {
        path:'/register',
        component: Register,
        name:'register'
    },
    {
        path:'/login',
        component: Login,
        name:'Login'
    },
    {
        path:'/Article',
        component: () => import('./views/Article.vue'),
        name:'Article'
    }
];

// const router = new Router({
//     routes: routes,
//     linkActiveClass: 'active'
// });

// export default router;
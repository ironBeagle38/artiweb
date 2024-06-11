import { createRouter, createWebHistory } from 'vue-router';
import Home from '../components/Home.vue';
import Test from '../components/Test.vue';
import NotFound from '../components/NotFound.vue';

const routes = [
  { path: '/', component: Home },
  { path: '/test', component: Test },
  { path: '/notFound', component: NotFound },

  { path: '/:catchAll(.*)', redirect: '/notFound' } // Redirection vers la page d'accueil
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;

import { setupLayouts } from 'virtual:generated-layouts'
import { createRouter, createWebHistory } from 'vue-router/auto'
import { useAuthStore } from '@/stores/auth'
import { setupGuards } from './guards'

function recursiveLayouts(route) {
  if (route.children) {
    for (let i = 0; i < route.children.length; i++)
      route.children[i] = recursiveLayouts(route.children[i])
    
    return route
  }
  
  return setupLayouts([route])[0]
}

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  scrollBehavior(to) {
    if (to.hash)
      return { el: to.hash, behavior: 'smooth', top: 60 }
    
    return { top: 0 }
  },
  extendRoutes: pages => [
    ...[...pages].map(route => recursiveLayouts(route)),
  ],
})

setupGuards(router)
export { router }
//TODO transféré dans un guard
// router.beforeEach((to, from, next) => {
//   const authStore = useAuthStore();
//   const isAuthenticated = authStore.isAuthenticated();
//
//   // Vérifiez si la route requiert l'authentification
//   if (to.matched.some(record => record.path.startsWith('/admin'))) {
//     if (!isAuthenticated) {
//       // Rediriger vers la page de connexion si l'utilisateur n'est pas authentifié
//       next({ path: '/login' });
//     } else {
//       next(); // tout est bon, continuer la navigation
//     }
//   } else {
//     next(); // assurez-vous de toujours appeler next() sinon la navigation ne se fera pas
//   }
// });

export default function (app) {
  app.use(router)
}

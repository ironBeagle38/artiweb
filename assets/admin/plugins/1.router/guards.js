// import { canNavigate } from '@layouts/plugins/casl'
import {useAuthStore} from "@/stores/auth.js"

export const setupGuards = router => {
  // Docs: https://router.vuejs.org/guide/advanced/navigation-guards.html#global-before-guards
  router.beforeEach((to, from, next) => {
    /*
     * If it's a public route, continue navigation. This kind of pages are allowed to be visited by logged-in & non-logged-in users. Basically, without any restrictions.
     * Examples of public routes are, 404, under maintenance, etc.
     */
    if (to.meta.public) {
      next()
      return
    }

    /**
     * Check if user is logged in by checking if token & user data exists in local storage
     * Feel free to update this logic to suit your needs
     */
    const authStore = useAuthStore()
    const isAuthenticated = authStore.isAuthenticated()

    /*
      If user is logged in and is trying to access login-like page, redirect to home
      else allow visiting the page
      (WARN: Don't allow executing further by return statement because next code will check for permissions)
     */
    if (to.meta.unauthenticatedOnly) {
      if (isAuthenticated) {
        next('/admin')  // Rediriger vers la page d'accueil
      } else {
        next()  // Continuer la navigation
      }
      return
    }

    // Gérer les routes d'administration
    if (to.matched.some(record => record.path.startsWith('/admin'))) {
      if (!isAuthenticated) {
        next({ path: '/login' })  // Rediriger vers la page de connexion si l'utilisateur n'est pas authentifié
      } else {
        next()  // L'utilisateur est authentifié, continuer la navigation
      }
      return
    }

    // // Vérifiez les autorisations avec canNavigate
    // if (!canNavigate(to) && to.matched.length) {
    //   if (isAuthenticated) {
    //     next({ name: 'not-authorized' })  // Rediriger vers une page non autorisée si l'utilisateur est authentifié mais n'a pas les permissions
    //   } else {
    //     next({
    //       name: 'login',
    //       query: {
    //         ...to.query,
    //         to: to.fullPath !== '/' ? to.path : undefined,
    //       },
    //     })  // Rediriger vers la page de connexion si l'utilisateur n'est pas authentifié
    //   }
    //   return
    // }

    next()
  })
}

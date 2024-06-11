import axios from 'axios'
import { useAuthStore } from '@/stores/auth'

const api = axios.create()
// const router = useRouter()

// Intercepteur pour ajouter le token d'authentification aux requêtes sortantes
api.interceptors.request.use(config => {
  const authStore = useAuthStore()

  if (authStore.token) {
    config.headers.Authorization = `Bearer ${authStore.token}`
    config.headers['Content-Type'] = 'application/ld+json'

  } else {
    // Si aucun token n'est trouvé, déconnectez et redirigez vers la page de connexion
   // authStore.logout()
   // router.push({ name: 'login' })
    //return Promise.reject(new Error('Utilisateur non authentifié'))
  }
  return config

}, error => {
  return Promise.reject(error)
})

export default api
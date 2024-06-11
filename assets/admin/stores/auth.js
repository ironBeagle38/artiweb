import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '@/utils/api.js'

export const useAuthStore = defineStore('auth', () => {
  const token= ref(localStorage.getItem('authToken'))
  const userData = ref(JSON.parse(localStorage.getItem('userData') || '[]'));

  const isAuthenticated = () => {
    // Vérifier si le token est présent
    if (token.value) {
      try {
        // Diviser le token en ses parties : en-tête, payload et signature
        const tokenParts = token.value.split('.')
        // Décoder la partie payload (base64)
        const payload = JSON.parse(atob(tokenParts[1]))
        // Vérifier si la date d'expiration (exp) est postérieure à l'heure actuelle
        return Date.now() < payload.exp * 1000 // convertir en millisecondes
      } catch (error) {
        // En cas d'erreur lors du décodage du token
        console.error('Error decoding the token:', error)

        localStorage.removeItem('authToken')
        localStorage.removeItem('userData')
        return false
      }
    }
    // Si le token n'est pas présent
    localStorage.removeItem('authToken')
    localStorage.removeItem('userData')
    return false
  }

  const login = (newToken) => {
    token.value = newToken
    localStorage.setItem('authToken', newToken)
  }

  const logout = () => {
    token.value = null
    localStorage.removeItem('authToken')
    localStorage.removeItem('userData')
  }

  const getUserData = () => userData.value;

  const setUserData = (data) => {
    userData.value = data
    localStorage.setItem('userData', JSON.stringify(data))
  }

  const updateUserData = (newData) => {
    const { id, roles } = userData.value

    Object.assign(userData.value, newData)
    userData.value.id = id
    userData.value.roles = roles

    localStorage.setItem('userData', JSON.stringify(userData.value))
  }

  const authenticateUser = async (credentials) => {
    try {
      const response = await api.post('/api/login', credentials)
      // Vérifiez que la réponse est correcte
      if (response.status === 200 && response.data && response.data.token) {
        login(response.data.token)
        setUserData(response.data.userData)
      } else {
        // Gérer les réponses non attendues
        throw new Error('Invalid response from server')
      }
    } catch (error) {
      // Relancer l'erreur pour que le bloc try...catch externe puisse la gérer
      throw new Error('Authentication failed: ' + (error.response?.data?.message || error.message))
    }
  }

  return {
    token,
    userData,
    isAuthenticated,
    login,
    logout,
    authenticateUser,
    setUserData,
    getUserData,
    updateUserData,
  }
})

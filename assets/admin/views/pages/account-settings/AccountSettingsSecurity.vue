<script setup>
import api from "@/utils/api.js"
import { useAuthStore } from '@/stores/auth'

const authStore = useAuthStore()
const form = ref(null)

const passwordRules = [
  v => v.length >= 6 || 'Le mot de passe doit contenir au moins 6 caractères.',
  v => /[A-Z]/.test(v) || 'Le mot de passe doit contenir au moins une majuscule.',
  v => /[a-z]/.test(v) || 'Le mot de passe doit contenir au moins une minuscule.',
]

const snackbarVisible = ref(false)
const snackbarMessage = ref('')
const snackbarColor = ref('')

const isCurrentPasswordVisible = ref(false)
const isNewPasswordVisible = ref(false)
const isConfirmPasswordVisible = ref(false)
const currentPassword = ref('')
const newPassword = ref('')
const confirmPassword = ref('')

const passwordRequirements = [
  'Au moins 6 caractères',
  'Au moins un caractère minuscule et un majuscule',
]

const changePassword = async () => {
  const { valid } = await form.value.validate();
  if (valid) {
    const id = ref(authStore.getUserData().id)

    const data = {
      currentPassword: currentPassword.value,
      newPassword: newPassword.value
    }

    try {
      const url = `/api/users/${id.value}/changePassword`
      const response = await api.put(url, data)

      if (response.status === 200 && response.data.message) {
        snackbarMessage.value = response.data.message
        snackbarColor.value = 'success'
        snackbarVisible.value = true
      } else {
        // Gérer les réponses non attendues
        throw new Error('Réponse invalide du serveur.')
      }
    } catch (error) {
      if (error.response) {
        console.error('API responded with error:', error.response.data)

        snackbarMessage.value = error.response.data.error || error.response.data.detail || 'Une erreur est survenue lors du changement de mot de passe.'
        snackbarColor.value = 'error'
        snackbarVisible.value = true

      } else {
        console.error('No response received:', error.request);

        snackbarMessage.value = 'Erreur inattendue.'
        snackbarColor.value = 'error'
        snackbarVisible.value = true
      }
    }
  }
}
</script>

<template>
  <VRow>
    <!-- SECTION: Change Password -->
    <VCol cols="12">
      <VCard title="Changer le mot de passe">
        <VForm
          ref="form"
          validate-on="submit lazy"
          @submit.prevent="changePassword"
        >
          <VCardText class="pt-0">
            <!-- 👉 Current Password -->
            <VRow>
              <VCol
                cols="12"
                md="6"
              >
                <!-- 👉 current password -->
                <AppTextField
                  v-model="currentPassword"
                  :type="isCurrentPasswordVisible ? 'text' : 'password'"
                  :append-inner-icon="isCurrentPasswordVisible ? 'tabler-eye-off' : 'tabler-eye'"
                  label="Mot de passe actuel"
                  autocomplete="on"
                  placeholder="············"
                  :rules="[v => !!v || 'Mot de passe actuel requis.']"
                  @click:append-inner="isCurrentPasswordVisible = !isCurrentPasswordVisible"
                />
              </VCol>
            </VRow>

            <!-- 👉 New Password -->
            <VRow>
              <VCol
                cols="12"
                md="6"
              >
                <!-- 👉 new password -->
                <AppTextField
                  v-model="newPassword"
                  :type="isNewPasswordVisible ? 'text' : 'password'"
                  :append-inner-icon="isNewPasswordVisible ? 'tabler-eye-off' : 'tabler-eye'"
                  label="Nouveau Mot de passe"
                  autocomplete="on"
                  placeholder="············"
                  :rules="passwordRules"
                  @click:append-inner="isNewPasswordVisible = !isNewPasswordVisible"
                />
              </VCol>

              <VCol
                cols="12"
                md="6"
              >
                <!-- 👉 confirm password -->
                <AppTextField
                  v-model="confirmPassword"
                  :type="isConfirmPasswordVisible ? 'text' : 'password'"
                  :append-inner-icon="isConfirmPasswordVisible ? 'tabler-eye-off' : 'tabler-eye'"
                  label="Confirmer le nouveau mot de passe"
                  autocomplete="on"
                  placeholder="············"
                  :rules="[v => v === newPassword || 'Les mots de passe ne correspondent pas.']"
                  @click:append-inner="isConfirmPasswordVisible = !isConfirmPasswordVisible"
                />
              </VCol>
            </VRow>
          </VCardText>

          <!-- 👉 Password Requirements -->
          <VCardText>
            <h6 class="text-h6 text-medium-emphasis mb-4">
              Exigences du mot de passe :
            </h6>

            <VList class="card-list">
              <VListItem
                v-for="item in passwordRequirements"
                :key="item"
                :title="item"
                class="text-medium-emphasis"
              >
                <template #prepend>
                  <VIcon
                    size="10"
                    icon="tabler-circle-filled"
                  />
                </template>
              </VListItem>
            </VList>
          </VCardText>

          <!-- 👉 Action Buttons -->
          <VCardText class="d-flex flex-wrap gap-4">
            <VBtn type="submit">Enregistrer</VBtn>

            <VBtn
              type="reset"
              color="secondary"
              variant="tonal"
            >
              Annuler
            </VBtn>
          </VCardText>
        </VForm>
      </VCard>
    </VCol>

    <VSnackbar
      v-model="snackbarVisible"
      location="bottom end"
      variant="flat"
      :color="snackbarColor"
    >
      {{ snackbarMessage }}
    </VSnackbar>
  </VRow>
</template>

<style lang="scss" scoped>
.card-list {
  --v-card-list-gap: 16px;
}

.server-close-btn {
  inset-inline-end: 0.5rem;
}
</style>

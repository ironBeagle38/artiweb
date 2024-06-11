<script setup>
import { useGenerateImageVariant } from '@core/composable/useGenerateImageVariant'
import { VNodeRenderer } from '@layouts/components/VNodeRenderer'
import { themeConfig } from '@themeConfig'
import authV2ResetPasswordIllustrationDark from '@images/pages/auth-v2-reset-password-illustration-dark.png'
import authV2ResetPasswordIllustrationLight from '@images/pages/auth-v2-reset-password-illustration-light.png'
import authV2MaskDark from '@images/pages/misc-mask-dark.png'
import authV2MaskLight from '@images/pages/misc-mask-light.png'
import api from "@/utils/api.js"

definePage({
  meta: {
    layout: 'blank',
    unauthenticatedOnly: true,
  },
})

const route = useRoute()
const router = useRouter()

const token = ref(route.query.token)

const form = ref({
  newPassword: '',
  confirmPassword: '',
})

const errors = ref({
  newPassword: '',
  confirmPassword: '',
})

const authThemeImg = useGenerateImageVariant(authV2ResetPasswordIllustrationLight, authV2ResetPasswordIllustrationDark)
const authThemeMask = useGenerateImageVariant(authV2MaskLight, authV2MaskDark)
const isPasswordVisible = ref(false)
const isConfirmPasswordVisible = ref(false)
const snackbarVisible = ref(false)
const snackbarMessage = ref('')
const snackbarColor = ref('')

// Règles de validation
const passwordRules = [
  v => !!v || 'Le mot de passe est requis.',
  v => v.length >= 6 || 'Le mot de passe doit comporter au moins 6 caractères.',
  v => /[a-z]/.test(v) || 'Le mot de passe doit contenir au moins une lettre minuscule.',
  v => /[A-Z]/.test(v) || 'Le mot de passe doit contenir au moins une lettre majuscule.',
]

const confirmPasswordRules = [
  v => !!v || 'La confirmation du mot de passe est requise.',
  v => v === form.value.newPassword || 'Les mots de passe ne correspondent pas.',
]

const sendNewPassword = async () => {
  const formIsValid = form.value.newPassword !== '' && form.value.confirmPassword !== '' &&
      passwordRules.every(rule => rule(form.value.newPassword) === true) &&
      confirmPasswordRules.every(rule => rule(form.value.confirmPassword) === true)

  if (!formIsValid) {
    return
  }

  try {
    const response = await api.post(`/api/forgot-password/${token.value}`, { password: form.value.newPassword }, {
      headers: {
        'Content-Type': 'application/json',
      }
    })

    if (response.status === 204) {
      snackbarMessage.value = 'Mot de passe changé, vous allez être redirigé vers la page de connexion.'
      snackbarColor.value = "success"
    }

  } catch (error) {
    snackbarMessage.value = 'Une erreur est survenue, vous allez être redirigés vers la page de connexion.'
    snackbarColor.value = "error"

  } finally {
    snackbarVisible.value = true
    setTimeout(() => {
      router.push({ name: 'login' })
    }, 3000)
  }
}
</script>

<template>
  <RouterLink to="/">
    <div class="auth-logo d-flex align-center gap-x-3">
      <VNodeRenderer :nodes="themeConfig.app.logo" />
      <h1 class="auth-title">
        {{ themeConfig.app.title }}
      </h1>
    </div>
  </RouterLink>

  <VRow
    no-gutters
    class="auth-wrapper bg-surface"
  >
    <VCol
      md="8"
      class="d-none d-md-flex"
    >
      <div class="position-relative bg-background w-100 me-0">
        <div
          class="d-flex align-center justify-center w-100 h-100"
          style="padding-inline: 150px;"
        >
          <VImg
            max-width="451"
            :src="authThemeImg"
            class="auth-illustration mt-16 mb-2"
          />
        </div>

        <img
          class="auth-footer-mask flip-in-rtl"
          :src="authThemeMask"
          alt="auth-footer-mask"
          height="280"
          width="100"
        >
      </div>
    </VCol>

    <VCol
      cols="12"
      md="4"
      class="auth-card-v2 d-flex align-center justify-center"
    >
      <VCard
          flat
          :max-width="500"
          class="mt-12 mt-sm-0 pa-6"
      >
        <VCardText>
          <h4 class="text-h4 mb-1">
            Réinitialiser le mot de passe 🔒
          </h4>
        </VCardText>

        <VCardText>
          <VForm @submit.prevent="() => {}">
            <VRow>
              <!-- password -->
              <VCol cols="12">
                <AppTextField
                  v-model="form.newPassword"
                  autofocus
                  label="Nouveau Mot de Passe"
                  placeholder="············"
                  :type="isPasswordVisible ? 'text' : 'password'"
                  :append-inner-icon="isPasswordVisible ? 'tabler-eye-off' : 'tabler-eye'"
                  @click:append-inner="isPasswordVisible = !isPasswordVisible"
                  :rules="passwordRules"
                />
              </VCol>

              <!-- Confirm Password -->
              <VCol cols="12">
                <AppTextField
                  v-model="form.confirmPassword"
                  label="Confirmer le Mot de Passe"
                  placeholder="············"
                  :type="isConfirmPasswordVisible ? 'text' : 'password'"
                  :append-inner-icon="isConfirmPasswordVisible ? 'tabler-eye-off' : 'tabler-eye'"
                  @click:append-inner="isConfirmPasswordVisible = !isConfirmPasswordVisible"
                  :rules="confirmPasswordRules"
                />
              </VCol>

              <!-- Set password -->
              <VCol cols="12">
                <VBtn
                  block
                  @click="sendNewPassword"
                >
                  Définir un nouveau mot de passe
                </VBtn>
              </VCol>

              <!-- back to login -->
              <VCol cols="12">
                <RouterLink
                  class="d-flex align-center justify-center"
                  :to="{ name: 'login' }"
                >
                  <VIcon
                    icon="tabler-chevron-left"
                    size="20"
                    class="me-1 flip-in-rtl"
                  />
                  <span>Retour à la page de connexion</span>
                </RouterLink>
              </VCol>
            </VRow>
          </VForm>
        </VCardText>
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

<style lang="scss">
@use "@core/scss/template/pages/page-auth.scss";
</style>

<script setup>
import { useGenerateImageVariant } from '@core/composable/useGenerateImageVariant.js'
import authV2LoginIllustrationBorderedDark from '@images/pages/auth-v2-login-illustration-bordered-dark.png'
import authV2LoginIllustrationBorderedLight from '@images/pages/auth-v2-login-illustration-bordered-light.png'
import authV2LoginIllustrationDark from '@images/pages/auth-v2-login-illustration-dark.png'
import authV2LoginIllustrationLight from '@images/pages/auth-v2-login-illustration-light.png'
import authV2MaskDark from '@images/pages/misc-mask-dark.png'
import authV2MaskLight from '@images/pages/misc-mask-light.png'
import { VNodeRenderer } from '@layouts/components/VNodeRenderer.jsx'
import { themeConfig } from '@themeConfig'
import { useAuthStore } from '@/stores/auth'

definePage({
  meta: {
    layout: 'blank',
    unauthenticatedOnly: true,
  },
})

const authThemeImg = useGenerateImageVariant(authV2LoginIllustrationLight, authV2LoginIllustrationDark, authV2LoginIllustrationBorderedLight, authV2LoginIllustrationBorderedDark, true)
const authThemeMask = useGenerateImageVariant(authV2MaskLight, authV2MaskDark)

const authStore = useAuthStore()
const form = ref(null)
const isPasswordVisible = ref(false)

const username = ref('')
const password = ref('')
const remember = ref(false)
const loginError = ref(false)

const rules = {
  required: value => !!value || 'Ce champ est requis.',
  email: value => {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(value) || 'Adresse email invalide.';
  }
};

const router = useRouter()

const login = async () => {
  const { valid } = await form.value.validate()

  if (valid) {
    const credentials = {
      username: username.value,
      password: password.value
    }

    try {
      await authStore.authenticateUser(credentials)
      router.push({ name: 'admin' })
    } catch (error) {
      console.error("Authentication failed:", error)

      loginError.value = true
      username.value = ''
      password.value = ''
    }
  }
}
</script>

<template>
  <VSnackbar
    v-model="loginError"
    location="top end"
    variant="flat"
    color="error"
  >
    Email ou mot de passe invalide.
  </VSnackbar>

  <div class="auth-logo d-flex align-center gap-x-3">
    <VNodeRenderer :nodes="themeConfig.app.logo" />
    <h1 class="auth-title">
      {{ themeConfig.app.title }}
    </h1>
  </div>

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
          style="padding-inline: 6.25rem;"
        >
          <VImg
            max-width="613"
            :src="authThemeImg"
            class="auth-illustration mt-16 mb-2"
          />
        </div>

        <img
          class="auth-footer-mask"
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
        class="mt-12 mt-sm-0 pa-4"
      >
        <VCardText>
          <h4 class="text-h4 mb-1">
            Bienvenue 👋🏻
          </h4>
          <p class="mb-0">
            Connectez-vous à votre compte
          </p>
        </VCardText>
        <VCardText>
          <VForm
            ref="form"
            @submit.prevent="login"
            validate-on="submit lazy"
          >
            <VRow>
              <!-- email -->
              <VCol cols="12">
                <AppTextField
                  v-model="username"
                  :rules="[rules.required, rules.email]"
                  autofocus
                  label="Email"
                  type="email"
                  placeholder="johndoe@email.com"
                />
              </VCol>

              <!-- password -->
              <VCol cols="12">
                <AppTextField
                  v-model="password"
                  :rules="[rules.required]"
                  label="Mot de passe"
                  placeholder="············"
                  :type="isPasswordVisible ? 'text' : 'password'"
                  :append-inner-icon="isPasswordVisible ? 'tabler-eye-off' : 'tabler-eye'"
                  @click:append-inner="isPasswordVisible = !isPasswordVisible"
                />

                <div class="d-flex align-center flex-wrap justify-space-between mt-2 mb-4">
                  <VCheckbox
                    v-model="remember"
                    label="Se souvenir de moi"
                  />
                  <RouterLink
                    class="text-primary ms-2 mb-1"
                    :to="{ name: 'authentification-mot-de-passe-oublie' }"
                  >
                    Mot de passe oublié ?
                  </RouterLink>
                </div>

                <VBtn
                  block
                  type="submit"
                >
                  Connexion
                </VBtn>
              </VCol>
            </VRow>
          </VForm>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>

<style lang="scss">
@use "@core/scss/template/pages/page-auth.scss";
</style>

<script setup>
import { useGenerateImageVariant } from '@core/composable/useGenerateImageVariant'
import authV2VerifyEmailIllustrationDark from '@images/pages/auth-v2-verify-email-illustration-dark.png'
import authV2VerifyEmailIllustrationLight from '@images/pages/auth-v2-verify-email-illustration-light.png'
import authV2MaskDark from '@images/pages/misc-mask-dark.png'
import authV2MaskLight from '@images/pages/misc-mask-light.png'
import { VNodeRenderer } from '@layouts/components/VNodeRenderer'
import { themeConfig } from '@themeConfig'

definePage({
  meta: {
    layout: 'blank',
    unauthenticatedOnly: true,
  },
})

const authThemeImg = useGenerateImageVariant(authV2VerifyEmailIllustrationLight, authV2VerifyEmailIllustrationDark)
const authThemeMask = useGenerateImageVariant(authV2MaskLight, authV2MaskDark)

const route = useRoute()
const router = useRouter()
const email = ref(route.query.email)

const snackbarVisible = ref(false)
const snackbarMessage = ref('')
const snackbarColor = ref('')

const resendResetLink = async () => {
  try {
    await api.post('/api/forgot-password/', { email: email.value })
  } catch (error) {
    if (error.response) {
      console.error('API responded with error:', error.response.data)
    } else {
      console.error('Error without response:', error)
    }
  } finally {
    snackbarVisible.value = true
    snackbarMessage.value = 'Un email vient d\'être envoyé, vous allez être redirigés vers la page de connexion'
    snackbarColor.value = "success"
    setTimeout(() => {
      router.push({ name: 'login' })
    }, 3000)
  }
}
</script>

<template>
  <RouterLink :to="{ name: 'login' }">
    <div class="auth-logo d-flex align-center gap-x-3">
      <VNodeRenderer :nodes="themeConfig.app.logo" />
      <h1 class="auth-title">
        {{ themeConfig.app.title }}
      </h1>
    </div>
  </RouterLink>

  <VRow
    class="auth-wrapper bg-surface"
    no-gutters
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
            max-width="431"
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
            Vérifiez votre email ✉️
          </h4>
          <p class="text-body-1 mb-0">
            Le lien de réinitialisation a été envoyé à votre adresse mail: <span class="font-weight-medium text-high-emphasis">{{ email }}</span>
          </p>
          <p class="mt-2">
            Connectez-vous à votre boîte mail et suivez le lien pour poursuivre la réinitialisation de mot de passe.
          </p>

          <RouterLink :to="{ name: 'login' }">
            <VBtn
              block
              class="my-5"
            >
              Retour à la page de connexion
            </VBtn>
          </RouterLink>


          <div class="d-flex align-center justify-center">
            <span class="me-1">Vous n'avez pas reçu le mail ? </span><a href="#" @click.prevent="resendResetLink">Renvoyer</a>
          </div>
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

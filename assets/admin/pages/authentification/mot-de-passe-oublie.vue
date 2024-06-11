<script setup>
import { useGenerateImageVariant } from '@core/composable/useGenerateImageVariant.js'
import { VNodeRenderer } from '@layouts/components/VNodeRenderer.jsx'
import { themeConfig } from '@themeConfig'
import authV2ForgotPasswordIllustrationDark from '@images/pages/auth-v2-forgot-password-illustration-dark.png'
import authV2ForgotPasswordIllustrationLight from '@images/pages/auth-v2-forgot-password-illustration-light.png'
import authV2MaskDark from '@images/pages/misc-mask-dark.png'
import authV2MaskLight from '@images/pages/misc-mask-light.png'
import api from "@/utils/api.js"

const router = useRouter()
const form = ref(null)

const email = ref('')
const authThemeImg = useGenerateImageVariant(authV2ForgotPasswordIllustrationLight, authV2ForgotPasswordIllustrationDark)
const authThemeMask = useGenerateImageVariant(authV2MaskLight, authV2MaskDark)

definePage({
  meta: {
    layout: 'blank',
    unauthenticatedOnly: true,
  },
})

const rules = {
  required: value => !!value || 'Ce champ est requis.',
  email: value => {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
    return emailRegex.test(value) || 'Adresse email invalide.'
  }
}

const validateAndSendResetLink = async () => {
  const { valid } = await form.value.validate();
  if (valid) {
    await sendResetLink()
  }
}

const sendResetLink = async () => {
  try {
    const response = await api.post('/api/forgot-password/', { email: email.value }, {
      headers: {
        'Content-Type': 'application/json',
      }
    })

    if (response.status === 204) {
      router.push({
        name: 'authentification-verif-email',
        query: { email: email.value },
      })
    }

  } catch (error) {
    if (error.response) {
      console.error('API responded with error:', error.response.data)
    } else {
      console.error('Error without response:', error)
    }
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
            max-width="468"
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
      class="d-flex align-center justify-center"
    >
      <VCard
        flat
        :max-width="500"
        class="mt-12 mt-sm-0 pa-4"
      >
        <VCardText>
          <h4 class="text-h4 mb-1">
            Mot de passe oublié ? 🔒
          </h4>
          <p class="mb-0">
            Saisissez votre email pour réinitialiser votre mot de passe.
          </p>
        </VCardText>

        <VCardText>
          <VForm
            ref="form"
            @submit.prevent="validateAndSendResetLink"
            validate-on="submit lazy"
          >
            <VRow>
              <!-- email -->
              <VCol cols="12">
                <AppTextField
                  v-model="email"
                  autofocus
                  label="Email"
                  type="email"
                  placeholder="johndoe@email.com"
                  :rules="[rules.required, rules.email]"
                />
              </VCol>

              <!-- Reset link -->
              <VCol cols="12">
                <VBtn
                  block
                  type="submit"
                >
                  Envoyer le lien de réinitialisation
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
  </VRow>
</template>

<style lang="scss">
@use "@core/scss/template/pages/page-auth";
</style>

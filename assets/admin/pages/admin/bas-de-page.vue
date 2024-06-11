<script setup>
import logo from '@images/illustrations/sidebar-pic-2.png'
import api from "@/utils/api.js"

const refInputEl = ref()
const snackbarVisible = ref(false)
const snackbarMessage = ref('')
const snackbarColor = ref('')

const footerData = ref({
  id: '',
  logo: '',
  email: '',
  address: '',
  phoneNumber: '',
  city: '',
  postalCode: '',
  facebook: '',
  twitter: '',
  instagram: '',
  linkedin: '',
})

const changeAvatar = file => {
  const fileReader = new FileReader()
  const { files } = file.target

  if (files && files.length) {
    const file = files[0]
    const maxFileSize = 2 * 1024 * 1024

    if (file.size > maxFileSize) {
      snackbarVisible.value = true
      snackbarMessage.value = 'Le fichier est trop volumineux. Taille maximale autorisée : 2 Mo.'
      snackbarColor.value = "error"

      return
    }

    fileReader.readAsDataURL(file)
    fileReader.onload = () => {
      if (typeof fileReader.result === 'string') {
        footerData.value.newLogo = fileReader.result
        footerData.value.logoFile = file
      }
    }
  }
}

const resetAvatar = () => {
  footerData.value.newLogo = null
}

const sendFooterForm = async () => {
  let url = ''
  const formData = new FormData()

  for (const key in footerData.value) {
    formData.append(key, footerData.value[key] || '')
  }
  if (footerData.value.logoFile) {
    formData.append('file', footerData.value.logoFile)
  }

  if (footerData.value.id && footerData.value.id === 1) {
    url = '/api/footer/1/updateFooter'
  } else {
    url = '/api/footer'
  }

  try {
    const response = await api.post(url, formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    })

    if (response.status === 200) {
      snackbarVisible.value = true
      snackbarMessage.value = 'Footer mis à jour avec succès.'
      snackbarColor.value = "success"
    }
  } catch (error) {
    if (error.response) {
      console.error('API responded with error:', error.response.data)
      snackbarVisible.value = true
      snackbarMessage.value = 'Une erreur inattendue s\'est produite.'
      snackbarColor.value = "error"
    }
  }

}


onMounted(async () => {
  try {
    const response = await api.get('/api/footer/1');

    if (response.data && Object.keys(response.data).length > 0) {
      for (const key in response.data) {
        if (key !== 'logo' && response.data.hasOwnProperty(key)) {
          footerData.value[key] = response.data[key]
        }
      }

      footerData.value.logo = response.data.imageUrl ? response.data.imageUrl : logo

    } else {
      console.log('Les données du formulaire sont vides.')
      footerData.value.logo = logo
    }
  } catch (error) {
    console.log('Impossible de récupérer les données du formulaire.')
    footerData.value.logo = logo
  }
})
</script>

<template>
  <VRow>
    <VCol cols="12" class="mt-5">
      <h1>Bas de Page</h1>
    </VCol>
    <VCol cols="12" >
      <VCard>
        <VForm ref="form">
          <VCardText class="d-flex">
            <!-- 👉 Avatar -->
            <VAvatar
                rounded
                size="100"
                class="me-6"
                :image="footerData.newLogo ? footerData.newLogo : footerData.logo"
            />
            <!-- 👉 Upload Photo -->
            <form class="d-flex flex-column justify-center gap-4">
              <VCardTitle class="p-0">
                Logo de l'entreprise

              </VCardTitle>
              <div class="d-flex flex-wrap gap-4">
                <VBtn
                  color="primary"
                  size="small"
                  @click="refInputEl?.click()"
                >
                  <VIcon
                    icon="tabler-cloud-upload"
                    class="d-sm-none"
                  />
                  <span class="d-none d-sm-block">Télécharger une nouvelle photo</span>
                </VBtn>

                <input
                  ref="refInputEl"
                  type="file"
                  name="file"
                  accept=".jpeg,.png,.jpg,GIF"
                  hidden
                  @input="changeAvatar"
                >

                <VBtn
                  type="reset"
                  size="small"
                  color="secondary"
                  variant="tonal"
                  @click="resetAvatar"
                >
                  <span class="d-none d-sm-block">Annuler</span>
                  <VIcon
                    icon="tabler-refresh"
                    class="d-sm-none"
                  />
                </VBtn>
              </div>
              <p class="text-body-1 mb-0">
                JPG, GIF ou PNG autorisés. Taille maximale de 2Mo
              </p>
            </form>
          </VCardText>

          <VCardText class="pt-0">
            <VRow>
              <VCol
                  cols="12"
                  md="6"
              >
                <AppTextField
                  v-model="footerData.email"
                  label="Email de contact"
                  placeholder="jean.dufour@contact.fr"
                />
              </VCol>
              <VCol
                cols="12"
                md="6"
              >
                <AppTextField
                  v-model="footerData.phoneNumber"
                  label="Numéro de téléphone"
                  placeholder="06 48 25 36 15"
                />
              </VCol>
            </VRow>

            <VRow>
              <VCol
                cols="12"
                md="6"
              >
                <AppTextField
                  v-model="footerData.address"
                  label="Adresse de contact"
                  placeholder="115 rue du Moulin"
                />
              </VCol>
              <VCol
                cols="6"
                md="3"
              >
                <AppTextField
                  v-model="footerData.city"
                  label="Ville"
                  placeholder="Paris"
                />
              </VCol>
              <VCol
                  cols="6"
                  md="3"
              >
                <AppTextField
                    v-model="footerData.postalCode"
                    label="Code Postal"
                    placeholder="75000"
                />
              </VCol>
            </VRow>

            <VRow>
              <VCol
                  cols="12"
                  md="6"
              >
                <AppTextField
                  v-model="footerData.facebook"
                  label="Facebook"
                  placeholder="https://www.facebook.com/votre.nom.dutilisateur"
                />
              </VCol>
              <VCol
                  cols="12"
                  md="6"
              >
                <AppTextField
                  v-model="footerData.twitter"
                  label="Twitter"
                  placeholder="https://twitter.com/votre_nom_dutilisateur"
                />
              </VCol>
            </VRow>
            <VRow>
              <VCol
                  cols="12"
                  md="6"
              >
                <AppTextField
                  v-model="footerData.instagram"
                  label="Instagram"
                  placeholder="https://www.instagram.com/votre_nom_dutilisateur"
                />
              </VCol>
              <VCol
                  cols="12"
                  md="6"
              >
                <AppTextField
                    v-model="footerData.linkedin"
                    label="LinkedIn"
                    placeholder="https://www.linkedin.com/in/votre_nom_dutilisateur"
                />
              </VCol>
            </VRow>
          </VCardText>

          <!-- 👉 Action Buttons -->
          <VCardText class="d-flex flex-wrap gap-4">
            <VBtn
              @click="sendFooterForm"
            >
              {{ footerData.id ? 'Modifier' : 'Enregistrer'}}
            </VBtn>
          </VCardText>
        </VForm>

      </VCard>
    </VCol>
  </VRow>
  <VSnackbar
    v-model="snackbarVisible"
    location="bottom end"
    variant="flat"
    :color="snackbarColor"
  >
    {{ snackbarMessage }}
  </VSnackbar>
</template>

<style scoped>
  .p-0 {
    padding: 0;
  }
</style>
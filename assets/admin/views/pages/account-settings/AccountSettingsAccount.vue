<script setup>
import avatar1 from '@images/avatars/avatar-1.png'
import { useAuthStore } from '@/stores/auth'
import api from "@/utils/api.js"

let accountData = {}
const authStore = useAuthStore()

const accountDataLocal = ref({
  avatar: avatar1,
  firstName: '',
  lastName: '',
  email: '',
  phone: '',
  address: '',
  postCode: '',
  city: ''
})

const refInputEl = ref()
const snackbarVisible = ref(false)
const snackbarMessage = ref('')
const snackbarColor = ref('')

const resetForm = () => {
  accountDataLocal.value = structuredClone(accountData)
}

const sendProfilForm = async () => {
  const id = ref(authStore.getUserData().id)
  const formData = new FormData()

  for (const key in accountDataLocal.value) {
    formData.append(key, accountDataLocal.value[key])
  }

  if (accountDataLocal.value.avatarFile) {
    formData.append('avatarFile', accountDataLocal.value.avatarFile)
  }

  try {
    const url = `/api/users/${id.value}/updateUser`
    const response = await api.post(url, formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    })

    if (response.status === 200) {
      snackbarVisible.value = true
      snackbarMessage.value = 'Profil mis à jour avec succès.'
      snackbarColor.value = "success"
      authStore.updateUserData(accountDataLocal.value)
      accountData = accountDataLocal.value
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
      if (typeof fileReader.result === 'string')
        accountDataLocal.value.avatar = fileReader.result
        accountDataLocal.value.avatarFile = file
    }
  }
}
const resetAvatar = () => {
  accountDataLocal.value.avatar = accountData.avatar
}

onMounted(() => {
  const userData = authStore.getUserData()

  accountData = {
    avatar: userData.avatar || avatar1,
    firstName: userData.firstName || '',
    lastName: userData.lastName || '',
    email: userData.email || '',
    phone: userData.phone || '',
    address: userData.address || '',
    postCode: userData.postCode || '',
    city: userData.city || '',
  }

  accountDataLocal.value = structuredClone(accountData)
})
</script>

<template>
  <VRow>
    <VCol cols="12">
      <VCard>
        <VCardText class="d-flex">
          <!-- 👉 Avatar -->
          <VAvatar
            rounded
            size="100"
            class="me-6"
            :image="accountDataLocal.avatar"
          />

          <!-- 👉 Upload Photo -->
          <form class="d-flex flex-column justify-center gap-4">
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

        <VCardText class="pt-2">
          <!-- 👉 Form -->
          <VForm class="mt-3">
            <VRow>
              <!-- 👉 Email -->
              <VCol
                cols="12"
                md="6"
              >
                <AppTextField
                  v-model="accountDataLocal.email"
                  label="Email"
                  placeholder="johndoe@gmail.com"
                  type="email"
                  disabled
                />
              </VCol>
            </VRow>
            <VRow>
              <!-- 👉 First Name -->
              <VCol
                md="6"
                cols="12"
              >
                <AppTextField
                  v-model="accountDataLocal.firstName"
                  placeholder="John"
                  label="Prénom"
                />
              </VCol>

              <!-- 👉 Last Name -->
              <VCol
                md="6"
                cols="12"
              >
                <AppTextField
                  v-model="accountDataLocal.lastName"
                  placeholder="Doe"
                  label="Nom"
                />
              </VCol>

              <!-- 👉 Phone -->
              <VCol
                cols="12"
                md="6"
              >
                <AppTextField
                  v-model="accountDataLocal.phone"
                  label="Téléphone"
                  placeholder="06 44 85 64 97"
                />
              </VCol>

              <!-- 👉 Address -->
              <VCol
                cols="12"
                md="6"
              >
                <AppTextField
                  v-model="accountDataLocal.address"
                  label="Adresse"
                  placeholder="123 rue de la Liberté"
                />
              </VCol>

              <!-- 👉 City -->
              <VCol
                cols="12"
                md="6"
              >
                <AppTextField
                  v-model="accountDataLocal.city"
                  label="Ville"
                  placeholder="Paris"
                />
              </VCol>

              <!-- 👉 Post Code -->
              <VCol
                cols="12"
                md="6"
              >
                <AppTextField
                  v-model="accountDataLocal.postCode"
                  label="Code Postal"
                  placeholder="95000"
                />
              </VCol>

              <VCol
                cols="12"
                class="d-flex flex-wrap gap-4"
              >
                <VBtn @click="sendProfilForm">Enregistrer</VBtn>

                <VBtn
                  color="secondary"
                  variant="tonal"
                  type="reset"
                  @click.prevent="resetForm"
                >
                  Annuler
                </VBtn>
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

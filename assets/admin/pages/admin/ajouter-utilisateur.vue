<script setup>
import api from "@/utils/api.js"

const form = ref(null)

const snackbarVisible = ref(false)
const snackbarMessage = ref('')
const snackbarColor = ref('')

const isNewPasswordVisible = ref(true)
const isConfirmPasswordVisible = ref(true)

const rolesList = ['Admin', 'User']

const selectedRole = ref('User')
const email = ref('')
const newPassword = ref('')
const confirmPassword = ref('')

const rules = {
  required: value => !!value || 'Ce champ est requis.',
  email: value => {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
    return emailRegex.test(value) || 'Adresse email invalide.'
  },
  password: [
    value => value.length >= 6 || 'Le mot de passe doit contenir au moins 6 caractères.',
    value => /[A-Z]/.test(value) || 'Le mot de passe doit contenir au moins une majuscule.',
    value => /[a-z]/.test(value) || 'Le mot de passe doit contenir au moins une minuscule.',
  ],
  confirmPassword: [
    value => value === newPassword.value || 'Les mots de passe ne correspondent pas.'
  ]
}

const passwordRequirements = [
  'Au moins 6 caractères',
  'Au moins un caractère minuscule et un majuscule',
]

// Fonction pour générer un mot de passe aléatoire avec au moins une majuscule, une minuscule et un chiffre
const generatePassword = () => {
  // Définir la longueur du mot de passe
  const length = 8;

  // Définir les jeux de caractères
  const lowerCharset = 'abcdefghijklmnopqrstuvwxyz'; // Lettres minuscules
  const upperCharset = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'; // Lettres majuscules
  const digitCharset = '0123456789'; // Chiffres
  const allCharset = lowerCharset + upperCharset + digitCharset; // Tous les caractères

  let password = '';

  // S'assurer que le mot de passe contient au moins une minuscule, une majuscule et un chiffre
  password += lowerCharset[Math.floor(Math.random() * lowerCharset.length)]; // Ajouter une minuscule
  password += upperCharset[Math.floor(Math.random() * upperCharset.length)]; // Ajouter une majuscule
  password += digitCharset[Math.floor(Math.random() * digitCharset.length)]; // Ajouter un chiffre

  // Compléter le mot de passe pour atteindre la longueur souhaitée
  for (let i = 3; i < length; i++) {
    const randomIndex = Math.floor(Math.random() * allCharset.length);
    password += allCharset[randomIndex];
  }

  // Mélanger le mot de passe pour garantir un bon niveau de hasard
  password = password.split('').sort(() => Math.random() - 0.5).join('');

  newPassword.value = password
  confirmPassword.value = password
}


const createUser = async () => {
  const { valid } = await form.value.validate()

  if (valid) {
    try {
      let data = {
        email: email.value,
        password: newPassword.value,
        role: [],
      }

      if (selectedRole.value === 'Admin') {
        data.role = ["ROLE_ADMIN"]
      } else {
        data.role = ["ROLE_USER"]
      }

      const response = await api.post(`/api/users`, data)
      console.log(response.status)
      if (response.status === 200) {
        snackbarMessage.value = "Nouvel utilisateur créé avec succès."
        snackbarColor.value = 'success'
        snackbarVisible.value = true

        email.value = ''
        newPassword.value = ''
        confirmPassword.value = ''
        selectedRole.value = 'User'
      }
    } catch (error) {
      if (error.response) {
        console.error('API responded with error:', error.response.data);

        snackbarMessage.value = error.response.data.message || error.response.data.detail || 'Une erreur est survenue lors du changement de mot de passe.';
        snackbarColor.value = 'error';
        snackbarVisible.value = true;
      }
    }
  }
}
</script>

<template>
  <VRow>
    <VCol cols="12" class="mt-5">
      <h1>Créer un Utilisateur</h1>
    </VCol>
    <!-- SECTION: Change Password -->
    <VCol cols="12">
      <VCard>
        <VForm
          ref="form"
          validate-on="submit lazy"
          @submit.prevent="createUser"
        >
          <VCardText>
            <!-- 👉 Create User -->
            <VRow>
              <VCol
                cols="12"
                md="6"
              >
                <!-- 👉 Email -->
                <AppTextField
                  v-model="email"
                  label="Adresse email"
                  autocomplete="on"
                  placeholder="john.doe@gmail.com"
                  :rules="[rules.required, rules.email]"
                />
              </VCol>
              <VCol
                cols="12"
                md="6"
              >
                <!-- 👉 Roles -->
                <AppSelect
                  v-model="selectedRole"
                  :items="rolesList"
                  label="Roles"
                  placeholder="Select Item"
                />
              </VCol>
            </VRow>
            <VRow class="mt-5">
              <VCol class="pb-0">
                <VBtn @click="generatePassword">Mot de passe aléatoire</VBtn>
              </VCol>
            </VRow>
            <!-- 👉 Password -->
            <VRow>
              <VCol
                cols="12"
                md="6"
              >
                <!-- 👉 Password -->
                <AppTextField
                  v-model="newPassword"
                  :type="isNewPasswordVisible ? 'text' : 'password'"
                  :append-inner-icon="isNewPasswordVisible ? 'tabler-eye-off' : 'tabler-eye'"
                  label="Mot de passe"
                  autocomplete="on"
                  placeholder="············"
                  :rules="[rules.required, ...rules.password]"
                  @click:append-inner="isNewPasswordVisible = !isNewPasswordVisible"
                />
              </VCol>

              <VCol
                cols="12"
                md="6"
              >
                <!-- 👉 Confirm Password -->
                <AppTextField
                  v-model="confirmPassword"
                  :type="isConfirmPasswordVisible ? 'text' : 'password'"
                  :append-inner-icon="isConfirmPasswordVisible ? 'tabler-eye-off' : 'tabler-eye'"
                  label="Confirmer le nouveau mot de passe"
                  autocomplete="on"
                  placeholder="············"
                  :rules="[rules.required, ...rules.confirmPassword]"
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
</style>

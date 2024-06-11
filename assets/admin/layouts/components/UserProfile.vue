<script setup>
import { useAuthStore } from '@/stores/auth';

const router = useRouter();
const authStore = useAuthStore();
const userData = ref([])
const logout = async () => {
  authStore.logout()
  router.push({ name: 'login' })
}

const userRole = computed(() => {
  return userData.value.roles.includes("ROLE_ADMIN") ? "Admin" : "Utilisateur";
});

onMounted(() => {
  userData.value = authStore.getUserData()
})

watch(() => authStore.userData.value, (newValue) => {
  userData.value = newValue;
});
</script>

<template>
  <VBadge
    dot
    location="bottom right"
    offset-x="3"
    offset-y="3"
    bordered
    color="success"
  >
    <VAvatar
      class="cursor-pointer"
      color="primary"
      variant="tonal"
    >
      <VImg :src="userData.avatar || avatar" alt="User Image" />

      <!-- SECTION Menu -->
      <VMenu
        activator="parent"
        width="230"
        location="bottom end"
        offset="14px"
      >
        <VList>
          <!-- 👉 User Avatar & Name -->
          <VListItem>
            <template #prepend>
              <VListItemAction start>
                <VBadge
                  dot
                  location="bottom right"
                  offset-x="3"
                  offset-y="3"
                  color="success"
                >
                  <VAvatar
                    color="primary"
                    variant="tonal"
                  >
                    <VImg :src="userData.avatar || avatar" alt="User Image" />
                  </VAvatar>
                </VBadge>
              </VListItemAction>
            </template>

            <VListItemTitle class="font-weight-semibold">
              {{ userData.firstName || 'Prénom' }} {{ userData.lastName || 'Nom' }}
            </VListItemTitle>
            <VListItemSubtitle>{{ userRole }}</VListItemSubtitle>
          </VListItem>

          <VDivider class="my-2" />

          <!-- 👉 Settings -->
            <VListItem
              :to="{ name: 'admin-compte-params-tab', params: { tab: 'compte' } }"
            >
              <template #prepend>
                <VIcon
                  class="me-2"
                  icon="tabler-settings"
                  size="22"
                />
              </template>
              <VListItemTitle>Options</VListItemTitle>

            </VListItem>

          <!-- Divider -->
          <VDivider class="my-2" />
          <!-- 👉 Logout -->
          <VListItem @click="logout">
            <template #prepend>
              <VIcon
                class="me-2"
                icon="tabler-logout"
                size="22"
              />
            </template>

            <VListItemTitle>Déconnexion</VListItemTitle>
          </VListItem>
        </VList>
      </VMenu>
      <!-- !SECTION -->
    </VAvatar>
  </VBadge>
</template>

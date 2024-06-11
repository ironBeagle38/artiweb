<script setup>
import { ref, watch } from 'vue';
import { formatDate } from '@/utils/format.js';
import api from "@/utils/api.js";

const userList = ref([]);
const reviews = ref([]);

const options = ref({
  page: 1,
  itemsPerPage: 10,
  sortBy: [''],
  sortDesc: [false],
});

const props = defineProps({
  reviews: {
    type: Array,
    required: true,
  },
});

const headers = [
  {
    title: 'NOM',
    key: 'reviewer.displayName',
  },
  {
    title: 'COMMENTAIRE',
    key: 'comment',
  },
  {
    title: 'DATE CRéATION',
    key: 'createTime',
    align: 'center',
  },
  {
    title: 'DATE MAJ',
    key: 'updateTime',
    align: 'center',
  },
  {
    title: 'éTOILE',
    key: 'starRating',
    align: 'center',
    sortable: false,
  },
  {
    title: 'Afficher',
    key: 'statut',
    align: 'center',
    sortable: false,
  },
];

const onStatusChange = async (item) => {
  try {
    const updateResponse = await api.put(`/api/google_reviews/${item.reviewId}`, { statut: item.statut });
    if (updateResponse.status === 200) {
      console.log(updateResponse)
    }
  } catch (error) {
    console.error(`Failed to update review ID ${item.reviewId}:`, error.response ? error.response.data : error.message);
  }
};

watch(
  () => reviews.value.map(item => ({ reviewId: item.reviewId, statut: item.statut })),
  (newStatuses, oldStatuses) => {
    if (oldStatuses) {
      newStatuses.forEach((newStatus, index) => {
        const oldStatus = oldStatuses[index];
        if (oldStatus && newStatus.statut !== oldStatus.statut) {
          const item = reviews.value.find(review => review.reviewId === newStatus.reviewId);
          onStatusChange(item);
        }
      });
    }
  },
  { deep: true }
);

watch(() => props.reviews, (newReviews) => {
  reviews.value = newReviews;
}, { immediate: true });
</script>

<template>
  <VDataTable
      :headers="headers"
      :items="reviews"
      :items-per-page="options.itemsPerPage"
      :page="options.page"
      :options="options"
  >
    <!-- full name -->
    <template #item.reviewer.displayName="{ item }">
      <div class="d-flex align-center">
        <VAvatar
            size="32"
            :color="item.profilePhotoUrl ? '' : 'primary'"
            :class="item.profilePhotoUrl ? '' : 'v-avatar-light-bg primary--text'"
            :variant="!item.profilePhotoUrl ? 'tonal' : undefined"
        >
          <VImg
              v-if="item.profilePhotoUrl"
              :src="item.profilePhotoUrl"
          />
          <span v-else>{{ avatarText(item.displayName) }}</span>
        </VAvatar>
        <div class="d-flex flex-column ms-3">
          <span class="d-block font-weight-medium text-high-emphasis text-truncate">{{ item.displayName }}</span>
        </div>
      </div>
    </template>
    <!-- full name -->
    <template #item.createTime="{ item }">
      <div class="d-flex justify-center">
        <div class="d-flex flex-column">
          <span class="d-block">{{ formatDate(item.createTime) }}</span>
        </div>
      </div>
    </template>

    <template #item.updateTime="{ item }" class="justify-center">
      <div class="d-flex justify-center">
        <div class="d-flex flex-column">
          <span class="d-block">{{ formatDate(item.updateTime) }}</span>
        </div>
      </div>
    </template>

    <template #item.starRating="{ item }">
      <div class="d-flex justify-center">
        <div class="d-flex flex-column">
          <span
              class="d-block"
              :class="item.starRating"
          >
            {{ item.starRating }}
          </span>
        </div>
      </div>
    </template>

    <template #item.statut="{ item }">
      <div class="d-flex align-center justify-center">
        <VSwitch
            v-model="item.statut"
            :label="item.statut ? 'Visible' : 'Masqué'"
        />
      </div>
    </template>
    <template #bottom>
      <VCardText class="pt-5">
        <div class="d-flex flex-wrap justify-center justify-sm-space-between gap-y-2 mt-2">
          <VTextField
              v-model="options.itemsPerPage"
              label="Ligne par page:"
              type="number"
              min="-1"
              max="15"
              hide-details
              variant="underlined"
              style="max-inline-size: 8rem;min-inline-size: 5rem;"
          />

          <VPagination
              v-model="options.page"
              :total-visible="$vuetify.display.smAndDown ? 3 : 5"
              :length="Math.ceil(reviews.length / options.itemsPerPage)"
          />
        </div>
      </VCardText>
    </template>
  </VDataTable>
</template>

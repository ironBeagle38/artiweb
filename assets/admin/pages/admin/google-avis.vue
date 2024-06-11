<script setup>
import reviewsResponse from '@/utils/fakedata/googleReviews.js'
import DataTableGoogleReview from "@/views/pages/google-reviews/DataTableGoogleReview.vue";
import api from "@/utils/api.js";

// Références réactives pour les données et l'état de l'interface utilisateur
const reviews = ref([]);
const isImportDisabled = ref(false);
const snackbarVisible = ref(false);
const snackbarMessage = ref('');
const snackbarColor = ref('');

// Définir l'objet messages pour les différents états des messages de la snackbar
const messages = {
  noNewComments: 'Aucun nouveau commentaire ajouté.',
  commentsModified: (count) => `${count} commentaires modifiés avec succès.`,
  commentsAdded: (count) => `${count} commentaires ajoutés avec succès.`,
  commentsModifiedAndAdded: (count) => `${count} commentaires ajoutés/modifiés avec succès.`,
  error: 'Une erreur inattendue s\'est produite.',
  unknown: 'Opération inconnue.'
};

// Fonction pour récupérer et traiter les avis Google
const getGoogleReview = async () => {
  isImportDisabled.value = true; // Désactiver le bouton pour éviter les doublons

  // Extraire les avis de la réponse simulée
  const extractedReviews = reviewsResponse.reviews.map(review => ({
    reviewId: review.reviewId,
    displayName: review.reviewer.displayName,
    profilePhotoUrl: review.reviewer.profilePhotoUrl,
    starRating: review.starRating,
    createTime: review.createTime,
    updateTime: review.updateTime,
    statut: false
  }));

  // Séparer les avis à insérer et à mettre à jour
  const reviewsToInsert = [];
  const reviewsToUpdate = [];

  extractedReviews.forEach(extractedReview => {
    const existingReview = reviews.value.find(existingReview =>
        existingReview.reviewId === extractedReview.reviewId
    );

    if (existingReview) {
      // Ajouter aux mises à jour si l'avis existe et la date est différente
      if (new Date(existingReview.updateTime).getTime() !== new Date(extractedReview.updateTime).getTime()) {
        reviewsToUpdate.push(extractedReview);
      }
    } else {
      // Ajouter aux nouvelles insertions si l'avis n'existe pas
      reviewsToInsert.push(extractedReview);
    }
  });

  try {
    // Insérer les nouveaux avis
    if (reviewsToInsert.length > 0) {
      const insertResponse = await api.post('/api/google_reviews/collection', reviewsToInsert);
      if (insertResponse.status === 201) {
        snackbarVisible.value = true;
        snackbarMessage.value = messages.commentsAdded(reviewsToInsert.length);
        snackbarColor.value = "success";
      }
    }

    // Mettre à jour les avis existants
    let counterReviewUpdate = 0;
    for (const review of reviewsToUpdate) {
      try {
        const updateResponse = await api.put(`/api/google_reviews/${review.reviewId}`,  { data: review });
        if (updateResponse.status === 200) {
          counterReviewUpdate++;
        }
      } catch (error) {
        console.error(`Failed to update review ID ${review.reviewId}:`, error.response ? error.response.data : error.message);
      }
    }

    const insertCount = reviewsToInsert.length;
    const totalCount = counterReviewUpdate + insertCount;

    // Déterminer le message à afficher
    if (counterReviewUpdate === 0 && insertCount === 0) {
      snackbarMessage.value = messages.noNewComments;
    } else if (counterReviewUpdate > 0 && insertCount === 0) {
      snackbarMessage.value = messages.commentsModified(counterReviewUpdate);
    } else if (insertCount > 0 && counterReviewUpdate === 0) {
      snackbarMessage.value = messages.commentsAdded(insertCount);
    } else if (counterReviewUpdate > 0 && insertCount > 0) {
      snackbarMessage.value = messages.commentsModifiedAndAdded(totalCount);
    }

    snackbarVisible.value = true;
    snackbarColor.value = "success";

    // Recharger les avis après les insertions et les mises à jour
    const reviewsResponse = await api.get('/api/google_reviews');
    reviews.value = reviewsResponse.data['hydra:member'];
  } catch (error) {
    // Gérer les erreurs et afficher un message approprié
    if (error.response) {
      console.error('API responded with error:', error.response.data);
    } else {
      console.error('An unexpected error occurred:', error.message);
    }
    snackbarVisible.value = true;
    snackbarMessage.value = messages.error;
    snackbarColor.value = "error";
  }
};

// Charger les avis lors du montage du composant
onMounted(async () => {
  try {
    const {data} = await api.get('/api/google_reviews');
    reviews.value = data['hydra:member'];
  } catch (error) {
    if (error.response) {
      console.error('API responded with error:', error.response.data);
    } else {
      console.error('An unexpected error occurred:', error.message);
    }
  }
});
</script>

<template>
  <VRow>
    <VCol cols="12" class="mt-5">
      <h1>Avis Google</h1>
    </VCol>
    <VCol cols="12">
      <VCard>
        <VCol class="my-5 ml-2">
          <VBtn
              @click="getGoogleReview"
              :disabled="isImportDisabled"
          >
            <VIcon
                start
                icon="tabler-reload"
            />
            Récupérer les nouveaux commentaires
          </VBtn>
        </VCol>
        <DataTableGoogleReview :reviews="reviews"/>
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

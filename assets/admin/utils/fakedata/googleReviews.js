const reviewsResponse = {
  reviews: [
    {
      name: "accounts/1234567890/locations/9876543210/reviews/abcdef123456",
      reviewId: "abcdef123456",
      reviewer: {
        profilePhotoUrl: "https://www.jeancoutu.com/globalassets/revamp/photo/conseils-photo/20160302-01-reseaux-sociaux-profil/photo-profil.jpg",
        displayName: "John Doe",
        isAnonymous: false
      },
      starRating: "FIVE",
      comment: "Great service and friendly staff!",
      createTime: "2023-01-01T12:34:56Z",
      updateTime: "2026-01-02T12:34:56Z",
      reviewReply: {
        comment: "Thank you for your feedback!",
        updateTime: "2023-01-03T12:34:56Z"
      }
    },
    {
      name: "accounts/1234567890/locations/9876543210/reviews/ghijkl789012",
      reviewId: "ghljkh789012",
      reviewer: {
        profilePhotoUrl: "https://www.jeancoutu.com/globalassets/revamp/photo/conseils-photo/20160302-01-reseaux-sociaux-profil/photo-profil.jpg",
        displayName: "Jane Smith",
        isAnonymous: false
      },
      starRating: "FOUR",
      comment: "Nice ambiance, but the food was average.",
      createTime: "2023-02-01T12:34:56Z",
      updateTime: "2023-02-08T12:34:56Z",
      reviewReply: {
        comment: "We appreciate your feedback and will work on improving our food quality.",
        updateTime: "2023-02-03T12:34:56Z"
      },
      statut: false
    },
    {
      name: "accounts/1234567890/locations/9876543210/reviews/mnopqr345678",
      reviewId: "mnopqr3456sqddqs78",
      reviewer: {
        profilePhotoUrl: "https://www.jeancoutu.com/globalassets/revamp/photo/conseils-photo/20160302-01-reseaux-sociaux-profil/photo-profil.jpg",
        displayName: "Alice Johnson",
        isAnonymous: false
      },
      starRating: "THREE",
      comment: "It was okay, nothing special.",
      createTime: "2023-03-01T12:34:56Z",
      updateTime: "2024-03-08T12:34:56Z",
      reviewReply: {
        comment: "Thank you for your feedback. We strive to improve.",
        updateTime: "2023-03-03T12:34:56Z"
      },
      statut: false
    },
    {
      name: "accounts/1234567890/locations/9876543210/reviews/stuvwx901234",
      reviewId: "stuvwx901234",
      reviewer: {
        displayName: "Bob Brown",
        isAnonymous: false
      },
      starRating: "TWO",
      comment: "Not very satisfied with the service.",
      createTime: "2023-04-01T12:34:56Z",
      updateTime: "2023-04-02T12:34:56Z",
      reviewReply: {
        comment: "We apologize for the inconvenience. We will work on it.",
        updateTime: "2023-04-03T12:34:56Z"
      },
      statut: false
    },
    {
      name: "accounts/1234567890/locations/9876543210/reviews/yzabcd567890",
      reviewId: "yzabcd567890",
      reviewer: {
        displayName: "Carol White",
        isAnonymous: false
      },
      starRating: "ONE",
      comment: "Terrible experience.",
      createTime: "2023-05-01T12:34:56Z",
      updateTime: "2023-05-02T12:34:56Z",
      reviewReply: {
        comment: "We are very sorry to hear that. Please contact us to resolve this issue.",
        updateTime: "2023-05-03T12:34:56Z"
      },
      statut: false
    },
    {
      name: "accounts/1234567890/locations/9876543210/reviews/efghij678901",
      reviewId: "efghij678901",
      reviewer: {
        displayName: "David Green",
        isAnonymous: false
      },
      starRating: "FIVE",
      comment: "Absolutely wonderful experience!",
      createTime: "2023-06-01T12:34:56Z",
      updateTime: "2023-06-02T12:34:56Z",
      reviewReply: {
        comment: "Thank you for your kind words!",
        updateTime: "2023-06-03T12:34:56Z"
      },
      statut: true
    },
    {
      name: "accounts/1234567890/locations/9876543210/reviews/klmnop789012",
      reviewId: "klmnop789012",
      reviewer: {
        profilePhotoUrl: "https://www.jeancoutu.com/globalassets/revamp/photo/conseils-photo/20160302-01-reseaux-sociaux-profil/photo-profil.jpg",
        displayName: "Emma Brown",
        isAnonymous: false
      },
      starRating: "FOUR",
      comment: "Very good, will visit again.",
      createTime: "2023-07-01T12:34:56Z",
      updateTime: "2023-07-02T12:34:56Z",
      reviewReply: {
        comment: "We look forward to your next visit!",
        updateTime: "2023-07-03T12:34:56Z"
      },
      statut: false
    },
    {
      name: "accounts/1234567890/locations/9876543210/reviews/qrsuvw234567",
      reviewId: "qrsuvw234567",
      reviewer: {
        profilePhotoUrl: "https://www.jeancoutu.com/globalassets/revamp/photo/conseils-photo/20160302-01-reseaux-sociaux-profil/photo-profil_301783868.jpg",
        displayName: "Olivia Black",
        isAnonymous: false
      },
      starRating: "THREE",
      comment: "It was fine, nothing extraordinary.",
      createTime: "2023-08-01T12:34:56Z",
      updateTime: "2023-08-02T12:34:56Z",
      reviewReply: {
        comment: "Thank you for your feedback.",
        updateTime: "2023-08-03T12:34:56Z"
      },
      statut: true
    },
    {
      name: "accounts/1234567890/locations/9876543210/reviews/wxyzab345678",
      reviewId: "wxyzab345678",
      reviewer: {
        profilePhotoUrl: "https://www.jeancoutu.com/globalassets/revamp/photo/conseils-photo/20160302-01-reseaux-sociaux-profil/photo-profil_301783868.jpg",
        displayName: "Liam Gray",
        isAnonymous: false
      },
      starRating: "TWO",
      comment: "Not impressed at all.",
      createTime: "2023-09-01T12:34:56Z",
      updateTime: "2023-09-02T12:34:56Z",
      reviewReply: {
        comment: "We are sorry to hear that. We will try to improve.",
        updateTime: "2023-09-03T12:34:56Z"
      },
      statut: false
    },
    {
      name: "accounts/1234567890/locations/9876543210/reviews/cdefgh456789",
      reviewId: "cdefgh456789",
      reviewer: {
        profilePhotoUrl: "https://www.jeancoutu.com/globalassets/revamp/photo/conseils-photo/20160302-01-reseaux-sociaux-profil/photo-profil_301783868.jpg",
        displayName: "Sophia White",
        isAnonymous: false
      },
      starRating: "ONE",
      comment: "Worst experience ever.",
      createTime: "2023-10-01T12:34:56Z",
      updateTime: "2023-10-02T12:34:56Z",
      reviewReply: {
        comment: "We apologize for your experience. Please contact us to address your concerns.",
        updateTime: "2023-10-03T12:34:56Z"
      },
      statut: true
    }
  ],
  totalReviewCount: 10,
  averageRating: 3.0
};

export default reviewsResponse
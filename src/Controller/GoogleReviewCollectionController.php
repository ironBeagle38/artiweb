<?php

namespace App\Controller;

use App\DTO\GoogleReviewDTO;
use App\Entity\GoogleReview;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Service\StarRatingConverter;

class GoogleReviewCollectionController
{
    private EntityManagerInterface $entityManager;
    private SerializerInterface $serializer;
    private ValidatorInterface $validator;
    private StarRatingConverter $starRatingConverter;


    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer, ValidatorInterface $validator, StarRatingConverter $starRatingConverter)
    {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->starRatingConverter = $starRatingConverter;
    }

    public function __invoke(Request $request): JsonResponse
    {
        $data = $request->getContent();

        try {
            // Désérialiser la collection de GoogleReviewDto
            $googleReviewDtos = $this->serializer->deserialize($data, GoogleReviewDTO::class . '[]', 'json');
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Invalid JSON data'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $errors = [];
        foreach ($googleReviewDtos as $googleReviewDto) {
            try {
                // Convertir starRating
                $starRatingInt = $this->convertStarRating($googleReviewDto->starRating);
            } catch (\InvalidArgumentException $e) {
                $errors[] = "Invalid star rating for review ID " . $googleReviewDto->reviewId;
                continue;
            }

            // Mapper les données du DTO sur l'entité
            $googleReview = new GoogleReview();
            $googleReview->setReviewId($googleReviewDto->reviewId);
            $googleReview->setDisplayName($googleReviewDto->displayName);
            $googleReview->setProfilePhotoUrl($googleReviewDto->profilePhotoUrl);
            $googleReview->setStarRating($this->starRatingConverter->convertStarRating($googleReviewDto->starRating));
            $googleReview->setCreateTime($googleReviewDto->createTime);
            $googleReview->setUpdateTime($googleReviewDto->updateTime);
            $googleReview->setStatut($googleReviewDto->statut);

            // Valider l'entité
            $validationErrors = $this->validator->validate($googleReview);
            if (count($validationErrors) > 0) {
                $errors[] = (string) $validationErrors;
                continue;
            }

            $this->entityManager->persist($googleReview);
        }

        if (!empty($errors)) {
            return new JsonResponse(['errors' => $errors], JsonResponse::HTTP_BAD_REQUEST);
        }

        $this->entityManager->flush();

        $data = $this->getAllReviews();
        return new JsonResponse(['message' => 'Collection saved successfully', 'data' => $data], JsonResponse::HTTP_CREATED);
    }

    private function convertStarRating(string $starRating): int
    {
        $ratings = [
            'ONE' => 1,
            'TWO' => 2,
            'THREE' => 3,
            'FOUR' => 4,
            'FIVE' => 5,
        ];

        if (!isset($ratings[strtoupper($starRating)])) {
            throw new \InvalidArgumentException("Invalid star rating value");
        }

        return $ratings[strtoupper($starRating)];
    }

    #[Route("/api/google_reviews", name: "get_all_google_reviews", methods: ["GET"])]
    public function getAllReviews(): JsonResponse
    {
        $googleReviews = $this->entityManager->getRepository(GoogleReview::class)->findAll();

        $jsonContent = $this->serializer->serialize($googleReviews, 'json');

        return new JsonResponse($jsonContent, JsonResponse::HTTP_OK, [], true);
    }
}

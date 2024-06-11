<?php

namespace App\Controller;

use App\Entity\GoogleReview;
use App\Service\StarRatingConverter;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GoogleReviewPutController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private StarRatingConverter $starRatingConverter;

    public function __construct(EntityManagerInterface $entityManager, StarRatingConverter $starRatingConverter)
    {
        $this->entityManager = $entityManager;
        $this->starRatingConverter = $starRatingConverter;
    }

    /**
     * @throws Exception
     */
    #[Route("/api/google_reviews/{reviewId}", name: "put_google_review", methods: ["PUT"])]
    public function __invoke(string $reviewId, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Vérifiez si les données sont valides JSON
        if (json_last_error() !== JSON_ERROR_NONE) {
            return new JsonResponse(['error' => 'Invalid JSON data'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $googleReview = $this->entityManager->getRepository(GoogleReview::class)->findOneBy(['reviewId' => $reviewId]);

        if (!$googleReview) {
            return new JsonResponse(['error' => 'Review not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        // Si la requête ne contient que le statut
        if (isset($data['statut']) && count($data) === 1) {
            $googleReview->setStatut($data['statut']);
        } else {
            // Traiter les données complètes
            if (isset($data['displayName'])) {
                $googleReview->setDisplayName($data['displayName']);
            }
            if (isset($data['profilePhotoUrl'])) {
                $googleReview->setProfilePhotoUrl($data['profilePhotoUrl']);
            }
            if (isset($data['starRating'])) {
                try {
                    $googleReview->setStarRating($this->starRatingConverter->convertStarRating($data['starRating']));
                } catch (\InvalidArgumentException $e) {
                    return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
                }
            }
            if (isset($data['createTime'])) {
                $googleReview->setCreateTime(new \DateTimeImmutable($data['createTime']));
            }
            if (isset($data['updateTime'])) {
                $googleReview->setUpdateTime(new \DateTimeImmutable($data['updateTime']));
            }
            if (isset($data['statut'])) {
                $googleReview->setStatut($data['statut']);
            }
        }

        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Review updated'], JsonResponse::HTTP_OK);
    }
}
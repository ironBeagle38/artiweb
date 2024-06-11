<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Controller\GoogleReviewCollectionController;
use App\Controller\GoogleReviewPutController;
use App\Repository\GoogleReviewRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/google_reviews/collection',
            controller: GoogleReviewCollectionController::class,
            security: "is_granted('ROLE_ADMIN')",
            name: 'post_collection',
        ),
        new GetCollection(),
        new Put(
            uriTemplate: '/google_reviews/{reviewId}',
            controller: GoogleReviewPutController::class,
            security: "is_granted('ROLE_ADMIN')",
            name: 'put_google_review'
        )
    ],
    normalizationContext: ['groups' => ['read:GoogleReview']],
    denormalizationContext: ['groups' => ['write:GoogleReview']]
)]
#[ORM\Entity(repositoryClass: GoogleReviewRepository::class)]
class GoogleReview
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['read:GoogleReview', 'write:GoogleReview'])]
    private ?string $reviewId;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['read:GoogleReview', 'write:GoogleReview'])]
    private ?string $displayName = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['read:GoogleReview', 'write:GoogleReview'])]
    private ?string $profilePhotoUrl = null;

    #[ORM\Column(type: 'integer')]
    #[Groups(['read:GoogleReview', 'write:GoogleReview'])]
    private ?int $starRating;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups(['read:GoogleReview', 'write:GoogleReview'])]
    private ?\DateTimeImmutable $createTime;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups(['read:GoogleReview', 'write:GoogleReview'])]
    private ?\DateTimeImmutable $updateTime;

    #[ORM\Column(type: 'boolean')]
    #[Groups(['read:GoogleReview', 'write:GoogleReview'])]
    private bool $statut = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReviewId(): ?string
    {
        return $this->reviewId;
    }

    public function setReviewId(string $reviewId): self
    {
        $this->reviewId = $reviewId;

        return $this;
    }

    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    public function setDisplayName(string $displayName): self
    {
        $this->displayName = $displayName;

        return $this;
    }

    public function getProfilePhotoUrl(): ?string
    {
        return $this->profilePhotoUrl;
    }

    public function setProfilePhotoUrl(?string $profilePhotoUrl): self
    {
        $this->profilePhotoUrl = $profilePhotoUrl;

        return $this;
    }

    public function getStarRating(): ?int
    {
        return $this->starRating;
    }

    public function setStarRating(int $starRating): self
    {
        $this->starRating = $starRating;

        return $this;
    }

    public function getCreateTime(): ?\DateTimeImmutable
    {
        return $this->createTime;
    }

    public function setCreateTime(\DateTimeImmutable $createTime): self
    {
        $this->createTime = $createTime;

        return $this;
    }

    public function getUpdateTime(): ?\DateTimeImmutable
    {
        return $this->updateTime;
    }

    public function setUpdateTime(\DateTimeImmutable $updateTime): self
    {
        $this->updateTime = $updateTime;

        return $this;
    }

    public function getStatut(): ?bool
    {
        return $this->statut;
    }

    public function setStatut(bool $statut): self
    {
        $this->statut = $statut;

        return $this;
    }
}

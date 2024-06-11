<?php

namespace App\DTO;

use Symfony\Component\Serializer\Annotation\Groups;

class GoogleReviewDTO
{
    #[Groups(['write:GoogleReview'])]
    public string $reviewId;

    #[Groups(['write:GoogleReview'])]
    public string $displayName;

    #[Groups(['write:GoogleReview'])]
    public ?string $profilePhotoUrl = null;

    #[Groups(['write:GoogleReview'])]
    public string $starRating;

    #[Groups(['write:GoogleReview'])]
    public \DateTimeImmutable $createTime;

    #[Groups(['write:GoogleReview'])]
    public \DateTimeImmutable $updateTime;

    #[Groups(['write:GoogleReview'])]
    public bool $statut;
}

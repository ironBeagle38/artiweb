<?php

namespace App\Service;

class StarRatingConverter
{
    public function convertStarRating(string $starRating): int
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
}

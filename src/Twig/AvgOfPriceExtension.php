<?php

namespace App\Twig;

use App\Entity\Price;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AvgOfPriceExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [

            new TwigFilter('avg_price', [$this, 'getAvgPrice']),
        ];
    }

    public function getAvgPrice($value)
    {
        if (!is_iterable($value)) return 0;
        $count = 0;
        $sum = 0;
        /** @var Price $item */
        foreach ($value as $item) {
            if ($item->getPrice()) {
                    $sum += $item->getPrice();
                    $count++;
            }
        }
        if ($count <= 0) return 0;
        return ($sum / $count);
    }
}

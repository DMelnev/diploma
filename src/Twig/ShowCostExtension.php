<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class ShowCostExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('end_cost', [$this, 'cost']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('function_name', [$this, 'cost']),
        ];
    }

    public function cost($value)
    {
        return $value % 100 > 0
            ? number_format((float)$value / 100, 2, '.', '') . ' руб.'
            : (int)$value / 100 . ' руб.';
    }
}

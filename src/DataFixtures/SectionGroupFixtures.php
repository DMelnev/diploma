<?php

namespace App\DataFixtures;

use App\Entity\SectionGroup;
use Doctrine\Persistence\ObjectManager;

class SectionGroupFixtures extends BaseFixtures
{
    function loadData(ObjectManager $manager)
    {
        $this->createMany(SectionGroup::class, 3, function (SectionGroup $section) use ($manager) {
            $section
                ->setName($this->faker->colorName)
                ->setIcon('img/icons/departments/' . $this->faker->numberBetween(1,12) . '.svg');
        });
        $this->manager->flush();
    }
}

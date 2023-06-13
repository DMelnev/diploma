<?php

namespace App\DataFixtures;

use App\Entity\SectionGroup;
use Doctrine\Persistence\ObjectManager;

class SectionGroupFixtures extends BaseFixtures
{

    function loadData(ObjectManager $manager)
    {

        $items = ["Электроника", "Бытовая техника", "Гаджеты"];
        $i = 0;
        foreach ($items as $item) {
            $entity = $this->create(SectionGroup::class, function (SectionGroup $section) use($item) {
                $section
                    ->setName($item)
                    ->setIcon($this->faker->numberBetween(1, 12) . '.svg');
            });
            $this->addReference(SectionGroup::class . "|" . $i++, $entity);
        }
        $this->manager->flush();
    }
}

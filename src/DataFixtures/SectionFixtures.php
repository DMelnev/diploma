<?php

namespace App\DataFixtures;

use App\Entity\Section;
use App\Entity\SectionGroup;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SectionFixtures extends BaseFixtures implements DependentFixtureInterface
{

    function loadData(ObjectManager $manager)
    {

        $this->createMany(Section::class, 30, function (Section $section) use ($manager) {

            $section
                ->setName($this->faker->colorName)
                ->setIcon('img/icons/departments/' . $this->faker->numberBetween(1, 12) . '.svg');
            if ($this->faker->boolean(30)) {
                $section->setParent($this->getRandomReference(SectionGroup::class));
            }
        });
        $this->manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            SectionGroupFixtures::class,
        ];
    }

}

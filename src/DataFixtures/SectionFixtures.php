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

        $items = [
            1 => "Мониторы",
            2 => "Наушники",
            3 => "Стиральные машины",
            4 => "Аксессуары",
            5 => "Акустика",
            6 => "Фотоаппараты",
            7 => "Газовые плиты",
            8 => "Смартфоны",
            9 => "Микроволновки",
            10 => "Чайники",
            11 => "Бра",
            12 => "Кофемолки",
        ];
        $i = 0;
        foreach ($items as $id => $item) {
            $entity = $this->create(Section::class, function (Section $section) use ($id, $item) {
                $section
                    ->setName($item)
                    ->setIcon($id . '.svg');
                if ($this->faker->boolean(40)) {
                    $section->setParent($this->getRandomReference(SectionGroup::class));
                }
            });
            $this->addReference(Section::class . "|" . $i++, $entity);
        }
        $this->manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            SectionGroupFixtures::class,
        ];
    }

}

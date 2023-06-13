<?php


namespace App\DataFixtures;


use App\Entity\Unit;
use Doctrine\Persistence\ObjectManager;

class UnitFixtures extends BaseFixtures
{
    function loadData(ObjectManager $manager)
    {
        $items = ['шт.', 'мм.', 'м.', 'г.', 'кг.',];
        $i = 0;
        foreach ($items as $item) {
            $entity = $this->create(Unit::class, function (Unit $unit) use ($item) {
                $unit->setUnit($item);
            });
            $this->addReference(Unit::class . "|" . $i++, $entity);
        }
        $this->manager->flush();
    }
}
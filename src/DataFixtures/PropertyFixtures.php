<?php


namespace App\DataFixtures;


use App\Entity\Property;
use App\Entity\PropertyGroup;
use App\Entity\Unit;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PropertyFixtures extends BaseFixtures implements DependentFixtureInterface
{

    function loadData(ObjectManager $manager)
    {
        $array = [
            'Количество ядер',
            'Частота процессора',
            'Мощность',
            'Яркость',
            'Длина',
            'Ширина',
            'Высота',
            'Вес',
            'Цвет',
            'Диагональ',
            'DVI',
            'Разрешение',
        ];
        $i = 0;
        foreach ($array as $item) {
            $entity = $this->create(Property::class, function (Property $group) use ($item) {
                $group
                    ->setName($item)
                    ->setUnit($this->getRandomReference(Unit::class))
                    ->setPGroup($this->getRandomReference(PropertyGroup::class));
            });
            $this->addReference(Property::class . "|" . $i++, $entity);
        }
        $this->manager->flush();
    }


    public
    function getDependencies()
    {
        return [
            PropertyGroupFixtures::class,
            UnitFixtures::class,
        ];
    }
}
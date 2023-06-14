<?php


namespace App\DataFixtures;


use App\Entity\PropertyGroup;
use Doctrine\Persistence\ObjectManager;

class PropertyGroupFixtures extends BaseFixtures
{

    function loadData(ObjectManager $manager)
    {
        $array = [
            'Физические величины',
            'Видеокарты',
            'Пылесосы',
            'Компьютеры',
            'Электрические чайники'
        ];
        $i = 0;
        foreach ($array as $item) {
            $entity = $this->create(PropertyGroup::class, function (PropertyGroup $group) use ($item) {
                $group->setName($item);
            });
            $this->addReference(PropertyGroup::class . "|" . $i++, $entity);
        }
        $this->manager->flush();
    }
}
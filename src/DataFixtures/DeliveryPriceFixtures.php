<?php


namespace App\DataFixtures;


use App\Entity\DeliveryPrice;
use Doctrine\Persistence\ObjectManager;

class DeliveryPriceFixtures extends BaseFixtures
{

    function loadData(ObjectManager $manager)
    {
        $this->createMany(DeliveryPrice::class, 3, function (DeliveryPrice $price) {
            $price
                ->setDescription($this->faker->realText(20))
                ->setName($this->faker->lastName())
                ->setCost($this->faker->numberBetween(100, 1000))
                ->setMinCount($this->faker->numberBetween(2, 5))
                ->setMinPrice($this->faker->numberBetween(100, 1000));
        });
        $this->manager->flush();
    }
}
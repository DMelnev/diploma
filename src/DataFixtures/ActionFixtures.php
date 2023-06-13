<?php


namespace App\DataFixtures;


use App\Entity\Action;
use Doctrine\Persistence\ObjectManager;

class ActionFixtures extends BaseFixtures
{
    function loadData(ObjectManager $manager)
    {
        $this->createMany(Action::class, 5, function (Action $action) {
            $action
                ->setText($this->faker->realTextBetween(10, 30))
                ->setDiscount($this->faker->numberBetween(5, 50))
                ->setUntil($this->faker->dateTimeBetween('-5 days', '+1 month'))
                ->setPicture('slider.png');
        });
        $this->manager->flush();
    }
}
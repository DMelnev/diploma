<?php


namespace App\DataFixtures;


use App\Entity\Cart;
use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CartFixtures extends BaseFixtures implements DependentFixtureInterface
{

    function loadData(ObjectManager $manager)
    {
        $this->createMany(Cart::class, 100, function (Cart $cart) {
            $cart->setDone($this->faker->dateTimeBetween('-1 month'));
            if ($this->faker->boolean(75)) {
                $cart->setUser($this->getRandomReference(User::class));
            }
        });
        $this->manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class
        ];
    }
}
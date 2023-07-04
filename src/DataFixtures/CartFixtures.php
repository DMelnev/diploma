<?php


namespace App\DataFixtures;


use App\Entity\Cart;
use App\Entity\DeliveryPrice;
use App\Entity\PaySystem;
use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CartFixtures extends BaseFixtures implements DependentFixtureInterface
{

    function loadData(ObjectManager $manager)
    {
        $this->createMany(Cart::class, 50, function (Cart $cart) {
            if ($this->faker->boolean(95)) {
                $cart->setUser($this->getRandomReference(User::class));
            }

            if ($this->faker->boolean(80)) {
                $cart->setPayBy($this->getRandomReference(PaySystem::class));
                if ($this->faker->boolean(80)) {
                    $cart->setDeliveryBy($this->getRandomReference(DeliveryPrice::class));
                    $cart->setStatus($this->faker->boolean() ? 'Доставка' : 'Доставлено');
                    if ($this->faker->boolean()) {
                        $cart->setDone($this->faker->dateTimeBetween('-1 month'));
                        $cart->setStatus('Доставлено');
                    } else {
                        $cart->setStatus('Доставка');
                    }
                } else {
                    $cart->setStatus('В сборке');
                }
            } else {
                $cart->setStatus('Не оплачено');
            }
        });
        $this->manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            DeliveryPriceFixtures::class,
            paySystemFixtures::class,
        ];
    }
}
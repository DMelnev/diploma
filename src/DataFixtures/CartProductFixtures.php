<?php


namespace App\DataFixtures;


use App\Entity\Cart;
use App\Entity\CartProduct;
use App\Entity\Price;
use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CartProductFixtures extends BaseFixtures implements DependentFixtureInterface
{

    function loadData(ObjectManager $manager)
    {
        $this->createMany(CartProduct::class, 50, function (CartProduct $product) {
            /** @var User $seller */
            $seller = $this->getRandomReference(User::class);
            /** @var Price $price */
            $price = $this->getRandomReference(Price::class);
            $product
                ->setPrice($price)
                ->setSeller($seller)
                ->setCart($this->getRandomReference(Cart::class))
                ->setCount($this->faker->numberBetween(1, $price->getQuantity()));
        });
        $this->manager->flush();
    }

    public function getDependencies()
    {
        return [
            PriceFixtures::class,
            CartFixtures::class,
            UserFixtures::class,
        ];
    }
}
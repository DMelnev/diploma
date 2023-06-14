<?php


namespace App\DataFixtures;


use App\Entity\Cart;
use App\Entity\CartProduct;
use App\Entity\Price;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CartProductFixtures extends BaseFixtures implements DependentFixtureInterface
{

    function loadData(ObjectManager $manager)
    {
        $this->createMany(CartProduct::class, 100, function (CartProduct $product) {
            /** @var Price $price */
            $price = $this->getRandomReference(Price::class);
            $product
                ->setPrice($price)
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
        ];
    }
}
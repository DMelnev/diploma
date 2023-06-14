<?php


namespace App\DataFixtures;


use App\Entity\Price;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PriceFixtures extends BaseFixtures implements DependentFixtureInterface
{

    function loadData(ObjectManager $manager)
    {
        $this->createMany(Price::class, 100, function (Price $price) {
            $price
                ->setProduct($this->getRandomReference(Product::class))
                ->setSeller($this->getRandomReference(User::class))
                ->setPrice($this->faker->numberBetween(100, 50000))
                ->setQuantity($this->faker->numberBetween(1, 50000));
        });
        $this->manager->flush();
    }

    public function getDependencies()
    {
        return [
            ProductFixtures::class,
            UserFixtures::class,
        ];
    }
}
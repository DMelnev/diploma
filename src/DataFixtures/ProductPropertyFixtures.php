<?php


namespace App\DataFixtures;


use App\Entity\Product;
use App\Entity\ProductProperty;
use App\Entity\Property;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProductPropertyFixtures extends BaseFixtures implements DependentFixtureInterface
{

    function loadData(ObjectManager $manager)
    {
        $this->createMany(ProductProperty::class, 500, function (ProductProperty $property) {
            $property
                ->setProduct($this->getRandomReference(Product::class))
                ->setProperty($this->getRandomReference(Property::class));
            if ($this->faker->boolean(95)) {
                $property->setValue($this->faker->numberBetween(1, 7));
            }
        });
        $this->manager->flush();

    }

    public function getDependencies()
    {
        return [
            ProductFixtures::class,
            PropertyFixtures::class,
        ];
    }
}
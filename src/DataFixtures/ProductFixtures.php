<?php


namespace App\DataFixtures;


use App\Entity\Action;
use App\Entity\Product;
use App\Entity\Section;
use App\Entity\Unit;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends BaseFixtures implements DependentFixtureInterface
{

    function loadData(ObjectManager $manager)
    {
        $this->createMany(Product::class, 100, function (Product $product) {
            $product
                ->setName($this->faker->realTextBetween(5, 15))
                ->setLikes($this->faker->numberBetween(0, 100))
                ->setDislikes($this->faker->numberBetween(0, 100))
                ->setDescription($this->faker->realTextBetween(50, 500))
                ->setShortDescription($this->faker->realTextBetween(50, 200))
                ->setLimited($this->faker->boolean(10))
                ->setUnit($this->getRandomReference(Unit::class))
                ->setSection($this->getRandomReference(Section::class));
            if ($this->faker->boolean(70)) {
                $product->setAction($this->getRandomReference(Action::class));
            }
        });
        $this->manager->flush();
    }

    public function getDependencies()
    {
        return [
            UnitFixtures::class,
            SectionFixtures::class,
            ActionFixtures::class,
        ];
    }
}
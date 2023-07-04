<?php


namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\ProductPicture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PicturesFixtures extends BaseFixtures implements DependentFixtureInterface
{
    function loadData(ObjectManager $manager)
    {
        $array = [
            'bigGoods.png',
            'card.jpg',
            'slider.png',
            'videoca.png',
        ];
        for ($i = 0; $i < 200; $i++) {
            $this->create(ProductPicture::class, function (ProductPicture $picture) use ($array) {
                $picture
                    ->setProduct($this->getRandomReference(Product::class))
                    ->setLink($array[$this->faker->numberBetween(0, (count($array) - 1))]);
            });
        }
        $this->manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ProductFixtures::class,
        ];
    }
}
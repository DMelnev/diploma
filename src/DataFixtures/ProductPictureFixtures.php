<?php


namespace App\DataFixtures;


use App\Entity\Product;
use App\Entity\ProductPicture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProductPictureFixtures extends BaseFixtures implements DependentFixtureInterface
{

    function loadData(ObjectManager $manager)
    {
        $products = $this->getAllReferences(Product::class);
        foreach ($products as $product) {
            do {
                $this->create(ProductPicture::class, function (ProductPicture $picture) use ($product) {
                    $picture
                        ->setProduct($product)
                        ->setLink('card.jpg');
                });
            } while ($this->faker->boolean(70));
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
<?php


namespace App\DataFixtures;


use App\Entity\Price;
use App\Entity\Product;
use App\Entity\User;
use App\Service\RolesConst;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PriceFixtures extends BaseFixtures implements DependentFixtureInterface, RolesConst
{

    function loadData(ObjectManager $manager)
    {
        $this->createMany(Price::class, 50, function (Price $price) {
            /** @var User $user */
            $user = $this->getRandomReference(User::class);
            $user->setRoles(self::ROLE_SELLER);
            $price
                ->setProduct($this->getRandomReference(Product::class))
                ->setSeller($user)
                ->setPrice($this->faker->numberBetween(100, 500000))
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
<?php


namespace App\DataFixtures;


use App\Entity\Product;
use App\Entity\ShowHistory;
use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ShowHistoryFixtures extends BaseFixtures implements DependentFixtureInterface
{

    function loadData(ObjectManager $manager)
    {
        $this->createMany(ShowHistory::class, 100, function (ShowHistory $history) {
            $history->setProduct($this->getRandomReference(Product::class))
                ->setUser($this->getRandomReference(User::class))
                ->setDate($this->faker->dateTimeBetween('-1 week'));
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
<?php


namespace App\DataFixtures;


use App\Entity\DayOffer;
use App\Entity\Product;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class DayOfferFixtures extends BaseFixtures implements DependentFixtureInterface
{

    function loadData(ObjectManager $manager)
    {
        $this->create(DayOffer::class, function (DayOffer $dayOffer) {
            $dayOffer->setText($this->faker->realTextBetween(5, 20))
                ->setPicture('card.jpg')
                ->setProduct($this->getRandomReference(Product::class))
                ->setUntil($this->faker->dateTimeBetween('now', '+7 days'));
        });
        $this->manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ProductFixtures::class,
        ];
    }
}
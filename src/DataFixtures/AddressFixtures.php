<?php


namespace App\DataFixtures;


use App\Entity\Address;
use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AddressFixtures extends BaseFixtures implements DependentFixtureInterface
{

    function loadData(ObjectManager $manager)
    {
        $this->createMany(Address::class, 10, function (Address $address) {
            $array = explode(',', $this->faker->address);
            $address
                ->setUser($this->getRandomReference(User::class))
                ->setPostCode(trim($array[0]))
                ->setRegion(trim($array[1]))
                ->setCity(trim($array[2]))
                ->setStreet(trim($array[3]))
                ->setHouse(trim($array[4]));
            if ($this->faker->boolean) {
                $address->setFlat($this->faker->numberBetween(1, 999));
            }
        });
        $this->manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
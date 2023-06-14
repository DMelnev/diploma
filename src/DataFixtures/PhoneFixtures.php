<?php


namespace App\DataFixtures;


use App\Entity\Phone;
use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PhoneFixtures extends BaseFixtures implements DependentFixtureInterface
{

    function loadData(ObjectManager $manager)
    {
        $this->createMany(Phone::class, 30, function (Phone $phone) {
            $phone
                ->setNumber($this->faker->phoneNumber)
                ->setUser($this->getRandomReference(User::class));
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
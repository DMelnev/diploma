<?php


namespace App\DataFixtures;


use App\Entity\Passport;
use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PassportFixtures extends BaseFixtures implements DependentFixtureInterface
{


    function loadData(ObjectManager $manager)
    {
        $users = $this->getAllReferences(User::class);
        foreach ($users as $user) {
            $this->create(Passport::class, function (Passport $passport) use ($user) {
                /** @var User $user */
                $passport
                    ->setName((explode(' ', $user->getName()))[0])
                    ->setSurname((explode(' ', $user->getName()))[1])
                    ->setDate($this->faker->dateTimeBetween('-10 years', '-2 years'))
                    ->setCode($this->faker->numberBetween(100, 999) . '-' . $this->faker->numberBetween(100, 999))
                    ->setUser($user)
                    ->setGiven($this->faker->realText(50))
                    ->setNumber($this->faker->numberBetween(1000, 9999) . ' ' . $this->faker->numberBetween(100000, 999999));
            });

        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}
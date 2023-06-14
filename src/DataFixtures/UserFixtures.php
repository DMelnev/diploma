<?php


namespace App\DataFixtures;


use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends BaseFixtures
{
    private UserPasswordHasherInterface $passwordHash;


    /**
     * UserFixtures constructor.
     */
    public function __construct(UserPasswordHasherInterface $passwordHash)
    {
        $this->passwordHash = $passwordHash;
    }

    function loadData(ObjectManager $manager)
    {
        $this->create(User::class, function (User $user) {
            $user
                ->setName('admin')
                ->setRoles(['ROLE_USER', 'ROLE_ADMIN'])
                ->setEmail('admin@email.ru')
                ->setPassword($this->passwordHash->hashPassword($user, "123456"))
                ->setConfirmedAt($this->faker->dateTimeBetween('-1 week'));
        });
        $this->createMany(User::class, 10, function (User $user) {
            $user
                ->setName($this->faker->firstName() . ' ' . $this->faker->lastName())
                ->setRoles(['ROLE_USER'])
                ->setEmail($this->faker->email())
                ->setPassword($this->passwordHash->hashPassword($user, "123456"));
            if ($this->faker->boolean(70)) {
                $user->setConfirmedAt($this->faker->dateTimeBetween('-1 week'));
            }
        });
        $this->manager->flush();
    }
}
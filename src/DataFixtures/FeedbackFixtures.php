<?php


namespace App\DataFixtures;


use App\Entity\Feedback;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class FeedbackFixtures extends BaseFixtures implements DependentFixtureInterface
{

    function loadData(ObjectManager $manager)
    {
        $this->createMany(Feedback::class, 100, function (Feedback $feedback) {
            $feedback
                ->setUser($this->getRandomReference(User::class))
                ->setProduct($this->getRandomReference(Product::class))
                ->setText($this->faker->realText())
                ->setMark($this->faker->numberBetween(0, 5))
                ->setEmail($this->faker->email())
                ->setUserName($this->faker->name());
            if ($this->faker->boolean(80)) {
                $feedback->setPublishedAt($this->faker->dateTimeBetween('-10 days'));
            }
        });
        $this->manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            ProductFixtures::class,
        ];
    }
}
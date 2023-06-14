<?php


namespace App\DataFixtures;


use App\Entity\Banner;
use Doctrine\Persistence\ObjectManager;

class BannerFixtures extends BaseFixtures
{

    function loadData(ObjectManager $manager)
    {
        $this->createMany(Banner::class, 3, function (Banner $banner) {
            $banner
                ->setLink('/')
                ->setPicture('slider.png')
                ->setDescription($this->faker->realText(50))
                ->setSxpiriedAt($this->faker->dateTimeBetween('now', '+1 month'));
        });
        $this->manager->flush();
    }
}
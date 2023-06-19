<?php


namespace App\DataFixtures;


use App\Entity\Banner;
use Doctrine\Persistence\ObjectManager;

class BannerFixtures extends BaseFixtures
{

    function loadData(ObjectManager $manager)
    {
        $this->createMany(Banner::class, 10, function (Banner $banner) {
            $banner
                ->setLink('/')
                ->setPicture('slider.png')
                ->setDescription($this->faker->realText(50))
                ->setTitle($this->faker->realText(12))
                ->setExpiredAt($this->faker->dateTimeBetween('now', '+1 month'));
        });
        $this->manager->flush();
    }
}
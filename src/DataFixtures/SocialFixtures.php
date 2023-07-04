<?php


namespace App\DataFixtures;


use App\Entity\Social;
use Doctrine\Persistence\ObjectManager;

class SocialFixtures extends BaseFixtures
{

    function loadData(ObjectManager $manager)
    {
        $array = [
            'facebook' => [
                'link' => 'https://fb.com',
                'picture' => 'fb.svg'
            ],
            'twitter' => [
                'link' => 'https://twitter.com',
                'picture' => 'tw.svg'
            ],
            'linkedin' => [
                'link' => 'https://linkedin.com',
                'picture' => 'in.svg'
            ],
        ];
        foreach ($array as $name => $item) {
            $this->create(Social::class, function (Social $social) use ($name, $item) {
                $social
                    ->setDescription($name)
                    ->setPicture($item['picture'])
                    ->setLink($item['link']);
            });
        }

        $this->manager->flush();
    }
}
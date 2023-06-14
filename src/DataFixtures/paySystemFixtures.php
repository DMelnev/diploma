<?php


namespace App\DataFixtures;


use App\Entity\PaySystem;
use Doctrine\Persistence\ObjectManager;

class paySystemFixtures extends BaseFixtures
{

    function loadData(ObjectManager $manager)
    {
        $array = [
            'visa' => [
                'link' => '/',
                'picture' => 'visa.png'
            ],
            'mastercard' => [
                'link' => '/',
                'picture' => 'mastercard.png'
            ],
            'random' => [
                'link' => '/',
                'picture' => 'random.png'
            ],
        ];
        foreach ($array as $name => $item) {
            $this->create(PaySystem::class, function (PaySystem $paySystem) use ($name, $item) {
                $paySystem
                    ->setDescription($name)
                    ->setPicture($item['picture'])
                    ->setLink($item['link']);
            });
        }
        $this->manager->flush();
    }
}
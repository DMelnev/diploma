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
            'american' => [
                'link' => '/',
                'picture' => 'american.png'
            ],
            'delta' => [
                'link' => '/',
                'picture' => 'delta.png'
            ],
        ];
        $i = 0;
        foreach ($array as $name => $item) {
            $entity = $this->create(PaySystem::class, function (PaySystem $paySystem) use ($name, $item) {
                $paySystem
                    ->setDescription($name)
                    ->setPicture($item['picture'])
                    ->setLink($item['link']);
            });
            $this->addReference(PaySystem::class . '|' . $i++, $entity);
        }
        $this->manager->flush();
    }
}
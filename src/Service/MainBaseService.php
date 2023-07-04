<?php

namespace App\Service;


use App\Repository\PaySystemRepository;
use App\Repository\SectionRepository;
use App\Repository\SocialRepository;

class MainBaseService
{
    private SocialRepository $socialRepository;
    private PaySystemRepository $paySystemRepository;
    private SectionRepository $sectionRepository;

    /**
     * MainBaseService constructor.
     */
    public function __construct(
        SocialRepository $socialRepository,
        PaySystemRepository $paySystemRepository,
        SectionRepository $sectionRepository
    )
    {

        $this->socialRepository = $socialRepository;
        $this->paySystemRepository = $paySystemRepository;
        $this->sectionRepository = $sectionRepository;
    }

    public function getSocial(): ?array
    {
        return $this->socialRepository->findAll();
    }

    public function getPaySystem(): ?array
    {
        return $this->paySystemRepository->findAll();
    }

    public function getCategories(): ?array
    {
        return $this->sectionRepository->getArray();
    }

    public function getAllBase(): ?array
    {
        $result = [];
        $result['social'] = $this->getSocial();
        $result['paySystems'] = $this->getPaySystem();
        $result['categories'] = $this->getCategories();
        return $result;
    }

}
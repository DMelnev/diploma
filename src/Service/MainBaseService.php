<?php

namespace App\Service;


use App\Repository\PaySystemRepository;
use App\Repository\SectionRepository;
use App\Repository\SocialRepository;

class MainBaseService
{
    private SocialRepository $socialRepository;
    private PaySystemRepository $paySystemRepository;
    protected SectionRepository $sectionRepository;

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

    /**
     * @return array|null
     */
    public function getSocial(): ?array
    {
        return $this->socialRepository->findAll();
    }

    /**
     * @return array|null
     */
    public function getPaySystem(): ?array
    {
        return $this->paySystemRepository->findAll();
    }

    /**
     * @return array|null
     */
    public function getCategories(): ?array
    {
        return $this->sectionRepository->getArray();
    }

    /**
     * @param array $array
     * @return array|null
     */
    public function getAllBase(array $array = []): ?array
    {
        $result = [];
        $result['social'] = $this->getSocial();
        $result['paySystems'] = $this->getPaySystem();
        $result['categories'] = $this->getCategories();
        return array_merge($result, $array);
    }

}
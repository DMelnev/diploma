<?php

namespace App\Controller;

use App\Repository\BannerRepository;
use App\Repository\SectionGroupRepository;
use App\Repository\SectionRepository;
use App\Repository\SocialRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    private SocialRepository $socialRepository;
    private SectionRepository $sectionRepository;
    private BannerRepository $bannerRepository;

    /**
     * MainController constructor.
     */
    public function __construct(
        SocialRepository $socialRepository,
        SectionRepository $sectionRepository,
        BannerRepository $bannerRepository)
    {
        $this->socialRepository = $socialRepository;
        $this->sectionRepository = $sectionRepository;
        $this->bannerRepository = $bannerRepository;
    }

    /**
     * @Route("/", name="app_main")
     */
    public function index(): Response
    {
        return $this->render('main/index.html.twig', [
            'social' => $this->socialRepository->findAll(),
            'categories' => $this->sectionRepository->getArray(),
            'banners' => $this->bannerRepository->getRandom(3)
        ]);
    }
}

<?php

namespace App\Controller;

use App\Repository\BannerRepository;
use App\Repository\CartProductRepository;
use App\Repository\DayOfferRepository;
use App\Repository\ProductRepository;
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
    private CartProductRepository $cartProductRepository;
    private DayOfferRepository $dayOfferRepository;
    private ProductRepository $productRepository;

    /**
     * MainController constructor.
     */
    public function __construct(
        SocialRepository $socialRepository,
        SectionRepository $sectionRepository,
        BannerRepository $bannerRepository,
        CartProductRepository $cartProductRepository,
        DayOfferRepository $dayOfferRepository,
        ProductRepository $productRepository)
    {
        $this->socialRepository = $socialRepository;
        $this->sectionRepository = $sectionRepository;
        $this->bannerRepository = $bannerRepository;
        $this->cartProductRepository = $cartProductRepository;
        $this->dayOfferRepository = $dayOfferRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * @Route("/", name="app_main")
     */
    public function index(): Response

    {
        $dayOffer = $this->dayOfferRepository->getOffer();
        return $this->render('main/index.html.twig', [
            'social' => $this->socialRepository->findAll(),
            'categories' => $this->sectionRepository->getArray(),
            'banners' => $this->bannerRepository->getRandom($this->getParameter('main_count_of_banners')),
            'products' => $this->cartProductRepository->getTopProducts(),
            'dayOffer' => $dayOffer,
            'hotOffer' => $this->productRepository->getRandomAction($this->getParameter('main_count_of_actions')),
            'limited' => $this->productRepository->getLimited($this->getParameter('main_count_of_limited'), $dayOffer['id'])
        ]);
    }
}

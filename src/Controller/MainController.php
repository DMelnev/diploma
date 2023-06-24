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
        $hotOffer = $this->productRepository->getRandomAction(9);
        return $this->render('main/index.html.twig', [
            'social' => $this->socialRepository->findAll(),
            'categories' => $this->sectionRepository->getArray(),
            'banners' => $this->bannerRepository->getRandom(3),
            'products' => $this->cartProductRepository->getTopProducts(),
            'dayOffer' => $dayOffer,
            'hotOffer' => $hotOffer,
            'limited' => $this->productRepository->getLimited(16, $dayOffer['id'])
        ]);
    }
}

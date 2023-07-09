<?php


namespace App\Service;


use App\Repository\BannerRepository;
use App\Repository\CartProductRepository;
use App\Repository\DayOfferRepository;
use App\Repository\PaySystemRepository;
use App\Repository\ProductRepository;
use App\Repository\SectionRepository;
use App\Repository\SocialRepository;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class MainService extends MainBaseService
{
    private BannerRepository $bannerRepository;
    private ParameterBagInterface $parameterBag;
    private CartProductRepository $cartProductRepository;
    private DayOfferRepository $dayOfferRepository;
    private ProductRepository $productRepository;

    /**
     * MainService constructor.
     */
    public function __construct(
        SocialRepository $socialRepository,
        PaySystemRepository $paySystemRepository,
        SectionRepository $sectionRepository,
        BannerRepository $bannerRepository,
        ParameterBagInterface $parameterBag,
        CartProductRepository $cartProductRepository,
        DayOfferRepository $dayOfferRepository,
        ProductRepository $productRepository
    )
    {
        parent::__construct($socialRepository, $paySystemRepository, $sectionRepository);
        $this->bannerRepository = $bannerRepository;
        $this->parameterBag = $parameterBag;
        $this->cartProductRepository = $cartProductRepository;
        $this->dayOfferRepository = $dayOfferRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * @return array|null
     */
    public function getBanners(): ?array
    {
        return $this->bannerRepository->getRandom($this->parameterBag->get('main.count_of_banners'));
    }

    /**
     * @return array|null
     */
    public function getTopProducts(): ?array
    {
        return $this->cartProductRepository->getTopProducts();
    }

    /**
     * @return array|null
     */
    public function getDayOffer(): ?array
    {
        return $this->dayOfferRepository->getOffer();
    }

    /**
     * @return array|null
     */
    public function getHotOffer(): ?array
    {
        return $this->productRepository->getRandomAction($this->parameterBag->get('main.count_of_actions'));
    }

    /**
     * @param int $dayOfferId
     * @return array|null
     */
    public function getLimited(int $dayOfferId): ?array
    {
        $dayOfferId = ($dayOfferId) ?: 0;
        return $this->productRepository->getLimited($this->parameterBag->get('main.count_of_limited'), $dayOfferId);
    }

    /**
     * @return array|null
     */
    public function getAll(): ?array
    {
        $dayOffer = $this->getDayOffer();

        return $this->getAllBase([
            'banners' => $this->getBanners(),
            'products' => $this->getTopProducts(),
            'hotOffer' => $this->getHotOffer(),
            'dayOffer' => $dayOffer,
            'limited' => $this->getLimited($dayOffer['id']),
        ]);
    }
}
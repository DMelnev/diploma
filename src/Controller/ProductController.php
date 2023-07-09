<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Section;
use App\Entity\User;
use App\Form\FeedbackFormType;
use App\Form\FilterFormType;
use App\Form\Model\FeedbackFormModel;
use App\Form\Model\FilterFormModel;
use App\Repository\PaySystemRepository;
use App\Repository\ProductPropertyRepository;
use App\Repository\ProductRepository;
use App\Repository\SectionRepository;
use App\Repository\SocialRepository;
use App\Service\ProductService;
use App\Service\SortConst;
use App\Service\SortHandler;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController implements SortConst
{
    private SocialRepository $socialRepository;
    private SectionRepository $sectionRepository;
    private PaySystemRepository $paySystemRepository;
    private array $arrayBase;

    /**
     * ProductController constructor.
     */
    public function __construct(
        SocialRepository $socialRepository,
        SectionRepository $sectionRepository,
        PaySystemRepository $paySystemRepository
    )
    {
        $this->socialRepository = $socialRepository;
        $this->sectionRepository = $sectionRepository;
        $this->paySystemRepository = $paySystemRepository;
        $this->arrayBase = [
            'paySystems' => $this->paySystemRepository->findAll(),
            'social' => $this->socialRepository->findAll(),
            'categories' => $this->sectionRepository->getArray(),
        ];
    }

    /**
     * @Route("/product/catalog/{id}", defaults={"id" = null}, name="app_catalog")
     */
    public function catalog(
        Request $request,
        ProductService $service
    ): Response
    {
        $filter = [];
        $formModel = new FilterFormModel();
        $parameters = $service->catalogOptions();
        $form = $this->createForm(FilterFormType::class, $formModel, $parameters);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $range = explode(';', $form->get('price')->getData());
            if (isset($range[1])) {
                $filter = [
                    self::FILTER_RANGE => $range,
                    self::FILTER_TITLE => $form->get(self::FILTER_TITLE)->getData(),
                    self::FILTER_SELLER_ => $form->get(self::FILTER_SELLER_)->getData(),
                    self::FILTER_MEMORY_VALUE => $form->get(self::FILTER_MEMORY_VALUE)->getData(),
                ];
                $formModel = $form->getData();
                $parameters[self::FILTER_FROM_PRICE] = $range[0];
                $parameters[self::FILTER_TO_PRICE] = $range[1];
                $form = $this->createForm(FilterFormType::class, $formModel, $parameters);
            }
        }


        $array = $service->getCatalog($form, $request, $filter);
        $response = $this->renderForm('product/catalog.html.twig', $array);
        if (isset($array['sort'])) {
            $service->setCatalogCookie($array['sort'], $response);
        }
        return $response;
    }

    /**
     * @Route ("/product/{id}", name="app_product")
     */
    public function product(
        Product $product,
        Request $request,
        ProductService $service
    ): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $formModel = new FeedbackFormModel();
        if ($user) {
            $formModel->setEmail($user->getEmail());
            $formModel->setName($user->getName());
        }
        $form = $this->createForm(FeedbackFormType::class, $formModel);
        $form->handleRequest($request);

        return $this->renderForm('product/product.html.twig',
            $service->getProductAndFeedback($user, $product, $form));
    }

    /**
     * @Route ("/product/compare", name="app_compare")
     */
    public
    function compare(ProductService $service): Response
    {
        return $this->render('product/compare.html.twig', $service->getAllBase());
    }

}

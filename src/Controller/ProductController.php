<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Section;
use App\Form\FilterFormType;
use App\Form\Model\FilterFormModel;
use App\Repository\ProductPropertyRepository;
use App\Repository\ProductRepository;
use App\Repository\SectionRepository;
use App\Repository\SocialRepository;
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

    /**
     * ProductController constructor.
     */
    public function __construct(
        SocialRepository $socialRepository,
        SectionRepository $sectionRepository
    )
    {
        $this->socialRepository = $socialRepository;
        $this->sectionRepository = $sectionRepository;
    }

    /**
     * @Route("/product/catalog/{id}", defaults={"id" = null}, name="app_catalog")
     */
    public function catalog(
        Request $request,
        PaginatorInterface $paginator,
        ProductRepository $productRepository,
        ProductPropertyRepository $ppRepository,
        SortHandler $sortHandler,
        SectionRepository $sectionRepository
    ): Response
    {
        $filter = [];
        $minMax = [
            self::FILTER_MIN_PRICE => (int)($productRepository->getMinMaxPrice()[self::FILTER_MIN_PRICE] / 100),
            self::FILTER_MAX_PRICE => (int)(1 + $productRepository->getMinMaxPrice()[self::FILTER_MAX_PRICE] / 100),
        ];
        $formModel = new FilterFormModel();
        $form = $this->createForm(FilterFormType::class, $formModel, array_merge([
            self::FILTER_FROM_PRICE => (int)($productRepository->getMinMaxPrice()[self::FILTER_MIN_PRICE] / 100),
            self::FILTER_TO_PRICE => (int)(1 + $productRepository->getMinMaxPrice()[self::FILTER_MAX_PRICE] / 100),
        ], $minMax));
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
                $form = $this->createForm(FilterFormType::class, $formModel, array_merge([
                    self::FILTER_FROM_PRICE => $range[0],
                    self::FILTER_TO_PRICE => $range[1],
                ], $minMax));
            }
        }
        $sectionId = $request->get('id');
        if ($sectionId) {
            $filter[self::FILTER_SECTION] = (int)$sectionId;
        }
        /** @var Section $section */
        $section = ($sectionId) ? $sectionRepository->findOneBy(['id' => $sectionId]) : null;
        $sortArray = $sortHandler->handler($request);
        $qb = $productRepository->getSorted($sortArray, $filter);

        $pagination = $paginator->paginate(
            $qb,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', $this->getParameter('catalog.page_limit'))
        );
        $pagination->setTemplate('pagination.html.twig');

        $ppRepository->propertiesGroup('Объем памяти');


        $response = $this->renderForm('product/catalog.html.twig', [
            'social' => $this->socialRepository->findAll(),
            'categories' => $this->sectionRepository->getArray(),
            'pagination' => $pagination,
            'sort' => $sortArray,
            'form' => $form,
            'select' => $ppRepository->propertiesGroup(self::FILTER_TEMP),
            'section' => $section,
        ]);
        foreach ($sortArray as $key => $item) {
            $time = ($item == self::EMPTY) ? time() - 1 : time() + (30 * 24 * 60 * 60);
            $response->headers->setCookie(new Cookie($key, $item, $time));
        }
        return $response;
    }

    /**
     * @Route ("/product/{id}", name="app_product")
     */
    public function product(Product $product): Response
    {
        return $this->render('product/product.html.twig', [
            'social' => $this->socialRepository->findAll(),
            'categories' => $this->sectionRepository->getArray(),
            'product' => $product,
        ]);
    }

    /**
     * @Route ("/product/compare", name="app_compare")
     */
    public function compare(): Response
    {
        return $this->render('product/compare.html.twig', [
            'social' => $this->socialRepository->findAll(),
            'categories' => $this->sectionRepository->getArray(),
        ]);
    }

}

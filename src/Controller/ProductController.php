<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Repository\SectionRepository;
use App\Repository\SocialRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
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
     * @Route("/product/catalog", name="app_catalog")
     */
    public function catalog(
        Request $request,
        PaginatorInterface $paginator,
        ProductRepository $productRepository
    ): Response
    {
        $qb = $productRepository->getSorted();

        $pagination = $paginator->paginate(
            $qb,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', $this->getParameter('catalog.page_limit'))
        );
        $pagination->setTemplate('pagination.html.twig');

        return $this->render('product/catalog.html.twig', [
            'social' => $this->socialRepository->findAll(),
            'categories' => $this->sectionRepository->getArray(),
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route ("/product/{id}", name="app_product")
     */
    public function product(Product $product): Response
    {
        return $this->render('product/product.html.twig', [
            'social' => $this->socialRepository->findAll(),
            'categories' => $this->sectionRepository->getArray(),
        ]);
    }

    /**
     * @Route ("/product/compare", name="app_product")
     */
    public function compare(): Response
    {
        return $this->render('product/product.html.twig', [
            'social' => $this->socialRepository->findAll(),
            'categories' => $this->sectionRepository->getArray(),
        ]);
    }

}

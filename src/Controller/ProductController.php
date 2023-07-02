<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\FilterFormType;
use App\Form\Model\FilterFormModel;
use App\Repository\ProductPropertyRepository;
use App\Repository\ProductRepository;
use App\Repository\PropertyRepository;
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
     * @Route("/product/catalog", name="app_catalog")
     */
    public function catalog(
        Request $request,
        PaginatorInterface $paginator,
        ProductRepository $productRepository,
        ProductPropertyRepository $ppRepository,
        SortHandler $sortHandler
    ): Response
    {

        $sortArray = $sortHandler->handler($request);
        $qb = $productRepository->getSorted($sortArray);

        $pagination = $paginator->paginate(
            $qb,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', $this->getParameter('catalog.page_limit'))
        );
        $pagination->setTemplate('pagination.html.twig');

        $ppRepository->propertiesGroup('Объем памяти');

        $formModel = new FilterFormModel();
        $form = $this->createForm(FilterFormType::class, $formModel);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $range = explode(';', $form->get('price')->getData());
            $title = $form->get('title')->getData();
            $seller = $form->get('seller')->getData();

//            dd($title, $range, $seller);
        }

        $response = $this->renderForm('product/catalog.html.twig', [
            'social' => $this->socialRepository->findAll(),
            'categories' => $this->sectionRepository->getArray(),
            'pagination' => $pagination,
            'sort' => $sortArray,
            'form' => $form,
            'select' => $ppRepository->propertiesGroup('Объем памяти'),//что бы было как в задании
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

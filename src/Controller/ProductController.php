<?php

namespace App\Controller;

use App\Entity\Feedback;
use App\Entity\Product;
use App\Entity\Section;
use App\Entity\User;
use App\Form\FeedbackFormType;
use App\Form\FilterFormType;
use App\Form\Model\FeedbackFormModel;
use App\Form\Model\FilterFormModel;
use App\Repository\PaySystemRepository;
use App\Repository\ProductPictureRepository;
use App\Repository\ProductPropertyRepository;
use App\Repository\ProductRepository;
use App\Repository\SectionRepository;
use App\Repository\SocialRepository;
use App\Service\SortConst;
use App\Service\SortHandler;
use Doctrine\ORM\EntityManagerInterface;
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
        $section = ($sectionId) ? $sectionRepository->findOneById($sectionId) : null;
        $sortArray = $sortHandler->handler($request);
        $qb = $productRepository->getSorted($sortArray, $filter);

        $pagination = $paginator->paginate(
            $qb,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', $this->getParameter('catalog.page_limit'))
        );
        $pagination->setTemplate('pagination.html.twig');

        $ppRepository->propertiesGroup('Объем памяти');


        $response = $this->renderForm('product/catalog.html.twig', array_merge($this->arrayBase, [
            'pagination' => $pagination,
            'sort' => $sortArray,
            'form' => $form,
            'select' => $ppRepository->propertiesGroup(self::FILTER_TEMP),
            'section' => $section,
        ]));
        foreach ($sortArray as $key => $item) {
            $time = ($item == self::EMPTY) ? time() - 1 : time() + (30 * 24 * 60 * 60);
            $response->headers->setCookie(new Cookie($key, $item, $time));
        }
        return $response;
    }

    /**
     * @Route ("/product/{id}", name="app_product")
     */
    public function product(
        Product $product,
        ProductPictureRepository $pictureRepository,
        Request $request,
        EntityManagerInterface $entityManager
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
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var FeedbackFormModel $formModel */
            $formModel = $form->getData();
            $comment = new Feedback();
            $comment
                ->setUserName($formModel->getName())
                ->setText($formModel->getText())
                ->setEmail($formModel->getEmail())
                ->setProduct($product)
                ->setPublishedAt(new \DateTime('now'));
            if ($user) {
                $comment->setUser($user);
            }
            $entityManager->persist($comment);
            $entityManager->flush();

        }

        return $this->renderForm('product/product.html.twig', array_merge($this->arrayBase, [
            'product' => $product,
            'pictures' => $pictureRepository->findBy(['product' => $product]),
            'form' => $form,
        ]));
    }

    /**
     * @Route ("/product/compare", name="app_compare")
     */
    public
    function compare(): Response
    {
        return $this->render('product/compare.html.twig', array_merge($this->arrayBase, [

        ]));
    }

}

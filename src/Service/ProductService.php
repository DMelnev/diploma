<?php


namespace App\Service;


use App\Entity\Feedback;
use App\Entity\Product;
use App\Entity\Section;
use App\Entity\User;
use App\Form\Model\FeedbackFormModel;
use App\Repository\PaySystemRepository;
use App\Repository\ProductPictureRepository;
use App\Repository\ProductPropertyRepository;
use App\Repository\ProductRepository;
use App\Repository\SectionRepository;
use App\Repository\SocialRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ProductService extends MainBaseService implements SortConst
{
    private EntityManagerInterface $entityManager;
    private ProductPictureRepository $pictureRepository;
    private ProductRepository $productRepository;
    private PaginatorInterface $paginator;
    private ProductPropertyRepository $ppRepository;
    private SortHandler $sortHandler;
    private ParameterBagInterface $parameterBag;

    /**
     * ProductService constructor.
     */
    public function __construct(
        SocialRepository $socialRepository,
        PaySystemRepository $paySystemRepository,
        SectionRepository $sectionRepository,
        EntityManagerInterface $entityManager,
        ProductPictureRepository $pictureRepository,
        ProductRepository $productRepository,
        PaginatorInterface $paginator,
        ProductPropertyRepository $ppRepository,
        SortHandler $sortHandler,
        ParameterBagInterface $parameterBag
    )
    {
        parent::__construct($socialRepository, $paySystemRepository, $sectionRepository);
        $this->entityManager = $entityManager;
        $this->pictureRepository = $pictureRepository;
        $this->productRepository = $productRepository;
        $this->paginator = $paginator;
        $this->ppRepository = $ppRepository;
        $this->sortHandler = $sortHandler;
        $this->parameterBag = $parameterBag;
    }


    public function getProductAndFeedback(
        ?User $user,
        Product $product,
        FormInterface $form): ?array
    {

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
            try {
                $this->entityManager->persist($comment);
                $this->entityManager->flush();
            } catch (\Exception $e) {
                throw new BadRequestHttpException('что то пошло не так ' . $e->getMessage());
            }


        }
        return $this->getAllBase([
            'form' => $form,
            'product' => $product,
            'pictures' => $this->pictureRepository->findBy(['product' => $product]),
        ]);
    }

    public function getCatalog(FormInterface $form, Request $request, array $filter): ?array
    {


        $sectionId = $request->get('id');
        if ($sectionId) {
            $filter[self::FILTER_SECTION] = (int)$sectionId;
        }
        /** @var Section $section */
        $section = ($sectionId) ? $this->sectionRepository->findOneById($sectionId) : null;
        $sortArray = $this->sortHandler->handler($request);
        $qb = $this->productRepository->getSorted($sortArray, $filter);

        $pagination = $this->paginator->paginate(
            $qb,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', $this->parameterBag->get('catalog.page_limit'))
        );
        $pagination->setTemplate('pagination.html.twig');

        $this->ppRepository->propertiesGroup('Объем памяти');

        return $this->getAllBase([
            'pagination' => $pagination,
            'sort' => $sortArray,
            'form' => $form,
            'select' => $this->ppRepository->propertiesGroup(self::FILTER_TEMP),
            'section' => $section,
        ]);
    }

    public function catalogOptions(): array
    {
        return [
            self::FILTER_MIN_PRICE => (int)($this->productRepository->getMinMaxPrice()[self::FILTER_MIN_PRICE] / 100),
            self::FILTER_MAX_PRICE => (int)(1 + $this->productRepository->getMinMaxPrice()[self::FILTER_MAX_PRICE] / 100),
            self::FILTER_FROM_PRICE => (int)($this->productRepository->getMinMaxPrice()[self::FILTER_MIN_PRICE] / 100),
            self::FILTER_TO_PRICE => (int)(1 + $this->productRepository->getMinMaxPrice()[self::FILTER_MAX_PRICE] / 100),
        ];
    }

    public function setCatalogCookie($array, Response $response)
    {
        foreach ($array as $key => $item) {
            $time = ($item == self::EMPTY) ? time() - 1 : time() + (30 * 24 * 60 * 60);
            $response->headers->setCookie(new Cookie($key, $item, $time));
        }
    }
}
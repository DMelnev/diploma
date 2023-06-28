<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Model\UserEditFormModel;
use App\Form\UserEditFormType;
use App\Repository\CartRepository;
use App\Repository\SectionRepository;
use App\Repository\ShowHistoryRepository;
use App\Repository\SocialRepository;
use App\Service\MyFiles;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 * @package App\Controller
 * @IsGranted("ROLE_USER")
 */
class UserController extends AbstractController
{
    private SocialRepository $socialRepository;
    private SectionRepository $sectionRepository;
    private CartRepository $cartRepository;
    private ShowHistoryRepository $showHistoryRepository;

    public function __construct(
        SocialRepository $socialRepository,
        SectionRepository $sectionRepository,
        CartRepository $cartRepository,
        ShowHistoryRepository $showHistoryRepository)
    {
        $this->socialRepository = $socialRepository;
        $this->sectionRepository = $sectionRepository;
        $this->cartRepository = $cartRepository;
        $this->showHistoryRepository = $showHistoryRepository;
    }

    /**
     * @Route("/cabinet", name="app_user_account")
     */
    public function index(): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        return $this->render('user/account.html.twig', [
            'social' => $this->socialRepository->findAll(),
            'categories' => $this->sectionRepository->getArray(),
            'cart' => $this->cartRepository->getLast($user),
            'history' => $this->showHistoryRepository->getLast($user, $this->getParameter('user.show_prehistory')),
        ]);
    }

    /**
     * @Route ("/cabinet/profile", name="app_user_profile")
     */
    public function profile(
        Request $request,
        MyFiles $uploader,
        EntityManagerInterface $entityManager
    ): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $formType = new UserEditFormModel;
        $formType
            ->setPhone($user->getPhone())
            ->setName($user->getName())
            ->setEmail($user->getEmail());

        $form = $this->createForm(UserEditFormType::class, $formType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UserEditFormModel $userModel */
            $userModel = $form->getData();
            $image = $form->get('avatar')->getData();
            if ($image) {
                $filename = $uploader->uploadDeleteFile($image, $user->getAvatar());
                $user->setAvatar($filename);
            }
            $user
                ->setName($userModel->getName())
                ->setPhone($userModel->getPhone()
                );
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Профиль успешно сохранен');
        }
        return $this->renderForm('user/profile.html.twig', [
            'social' => $this->socialRepository->findAll(),
            'categories' => $this->sectionRepository->getArray(),
            'form' => $form,
        ]);
    }

    /**
     * @Route ("/cabinet/view", name="app_user_view_history")
     */
    public function viewHistory(): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        return $this->render('user/historyView.html.twig', [
            'social' => $this->socialRepository->findAll(),
            'categories' => $this->sectionRepository->getArray(),
            'history' => $this->showHistoryRepository->getLast($user, $this->getParameter('user.show_history')),
        ]);
    }

    /**
     * @Route ("/cabinet/order", name="app_user_order_history")
     */
    public function orderHistory(): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        return $this->render('user/historyOrder.html.twig', [
            'social' => $this->socialRepository->findAll(),
            'categories' => $this->sectionRepository->getArray(),
            'orders' => $this->cartRepository->getAll($user, $this->getParameter('user.cart_history')),
        ]);
    }
}

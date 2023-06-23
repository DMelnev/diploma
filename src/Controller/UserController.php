<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\CartRepository;
use App\Repository\SectionRepository;
use App\Repository\ShowHistoryRepository;
use App\Repository\SocialRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
            'history' => $this->showHistoryRepository->getLast($user),
        ]);
    }

    /**
     * @Route ("/cabinet/profile", name="app_user_profile")
     */
    public function profile(): Response
    {
        return $this->render('user/profile.html.twig', [
            'social' => $this->socialRepository->findAll(),
            'categories' => $this->sectionRepository->getArray(),
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
            'history' => $this->showHistoryRepository->getLast($user, 20),
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
            'orders' => $this->cartRepository->getAll($user),
        ]);
    }
}

<?php

namespace App\Controller;

use App\Repository\SectionRepository;
use App\Repository\SocialRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private SocialRepository $socialRepository;
    private SectionRepository $sectionRepository;

    public function __construct(
        SocialRepository $socialRepository,
    SectionRepository $sectionRepository)
    {
        $this->socialRepository = $socialRepository;
        $this->sectionRepository = $sectionRepository;
    }

    /**
     * @Route("/cabinet", name="app_user_account")
     */
    public function index(): Response
    {
        return $this->render('user/account.html.twig', [
            'controller_name' => 'UserController',
            'social' => $this->socialRepository->findAll(),
            'categories' => $this->sectionRepository->getArray(),
        ]);
    }

    /**
     * @Route ("/cabinet/profile", name="app_user_profile")
     */
    public function profile():Response
    {
        return $this->render('user/profile.html.twig', [
            'controller_name' => 'UserController',
            'social' => $this->socialRepository->findAll(),
            'categories' => $this->sectionRepository->getArray(),
        ]);
    }

    /**
     * @Route ("/cabinet/view", name="app_user_view_history")
     */
    public function viewHistory():Response
    {
        return $this->render('user/historyView.html.twig', [
            'controller_name' => 'UserController',
            'social' => $this->socialRepository->findAll(),
            'categories' => $this->sectionRepository->getArray(),
        ]);
    }

    /**
     * @Route ("/cabinet/order", name="app_user_order_history")
     */
    public function orderHistory():Response
    {
        return $this->render('user/historyOrder.html.twig', [
            'controller_name' => 'UserController',
            'social' => $this->socialRepository->findAll(),
            'categories' => $this->sectionRepository->getArray(),
        ]);
    }
}

<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Model\UserEditFormModel;
use App\Form\UserEditFormType;
use App\Service\CabinetService;
use League\Flysystem\FilesystemException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CabinetController
 * @package App\Controller
 * @IsGranted("ROLE_USER")
 */
class CabinetController extends AbstractController
{


    /**
     * @Route("/cabinet", name="app_user_account")
     */
    public function index(CabinetService $cabinetService): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        return $this->render('user/account.html.twig', $cabinetService->getIndex($user));
    }

    /**
     * @Route ("/cabinet/profile", name="app_user_profile")
     * @throws FilesystemException
     */
    public function profile(Request $request, CabinetService $cabinetService): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $formType = (new UserEditFormModel)
            ->setPhone($user->getPhone())
            ->setName($user->getName())
            ->setEmail($user->getEmail());
        $form = $this->createForm(UserEditFormType::class, $formType);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $cabinetService->handleProfile($form, $user);
            $this->addFlash('success', 'Профиль сохранен');
        }

        return $this->renderForm('user/profile.html.twig', $cabinetService->getProfile($form));
    }

    /**
     * @Route ("/cabinet/view", name="app_user_view_history")
     */
    public function viewHistory(CabinetService $cabinetService): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        return $this->render('user/historyView.html.twig', $cabinetService->getView($user));
    }

    /**
     * @Route ("/cabinet/order", name="app_user_order_history")
     */
    public function orderHistory(CabinetService $cabinetService): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        return $this->render('user/historyOrder.html.twig', $cabinetService->getOrders($user));
    }
}

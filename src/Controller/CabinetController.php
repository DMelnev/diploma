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
    private CabinetService $cabinetService;

    public function __construct(CabinetService $cabinetService)
    {
        $this->cabinetService = $cabinetService;
    }

    /**
     * @Route("/cabinet", name="app_user_account")
     */
    public function index(): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        return $this->render('user/account.html.twig', $this->cabinetService->getIndex($user));
    }

    /**
     * @Route ("/cabinet/profile", name="app_user_profile")
     * @throws FilesystemException
     */
    public function profile(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $formType = (new UserEditFormModel)
            ->setPhone($user->getPhone())
            ->setName($user->getName())
            ->setEmail($user->getEmail());

        $form = $this->createForm(UserEditFormType::class, $formType);
        $form->handleRequest($request);
        $properties = $this->cabinetService->getAllBase();
        $properties['form'] = $form;
        if ($form->isSubmitted() && $form->isValid()) {
            $properties['form'] = $this->cabinetService->handleProfile($form, $user);
            $this->addFlash('success', 'Профиль сохранен');
        }

        return $this->renderForm('user/profile.html.twig', $properties);
    }

    /**
     * @Route ("/cabinet/view", name="app_user_view_history")
     */
    public function viewHistory(): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        return $this->render('user/historyView.html.twig', $this->cabinetService->getView($user));
    }

    /**
     * @Route ("/cabinet/order", name="app_user_order_history")
     */
    public function orderHistory(): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        return $this->render('user/historyOrder.html.twig', $this->cabinetService->getOrders($user));
    }
}

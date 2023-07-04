<?php

namespace App\Controller;

use App\Service\MainService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    private MainService $service;

    /**
     * MainController constructor.
     */
    public function __construct(MainService $service)
    {
        $this->service = $service;
    }

    /**
     * @Route("/", name="app_main")
     */
    public function index(): Response
    {
        return $this->render('main/index.html.twig', $this->service->getAll());
    }

    /**
     * @Route("/about", name="app_about")
     */
    public function about(): Response
    {
        return $this->render('main/about.html.twig', $this->service->getAllBase());
    }

    /**
     * @Route("/contacts", name="app_contacts")
     */
    public function contacts(): Response
    {
        return $this->render('main/contacts.html.twig', $this->service->getAllBase());
    }
}

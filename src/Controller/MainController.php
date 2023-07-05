<?php

namespace App\Controller;

use App\Service\MainService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{

    /**
     * @Route("/", name="app_main")
     */
    public function index(MainService $service): Response
    {
        return $this->render('main/index.html.twig', $service->getAll());
    }

    /**
     * @Route("/about", name="app_about")
     */
    public function about(MainService $service): Response
    {
        return $this->render('main/about.html.twig', $service->getAllBase());
    }

    /**
     * @Route("/contacts", name="app_contacts")
     */
    public function contacts(MainService $service): Response
    {
        return $this->render('main/contacts.html.twig', $service->getAllBase());
    }
}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ServiceController extends AbstractController
{
    #[Route('/service', name: 'app_service')]
    public function index(): Response
    {
        return $this->render('service/index.html.twig', [
            'controller_name' => 'ServiceController',
        ]);
    }
     #[Route(path:'/name/{na}', name: 'name')]
    public function showservice($na): Response{
        return $this->render(view:'service/showService.html.twig',parameters:['name'=>$na]);
    }
     #[Route('/go-to-index', name: 'go_to_index')]
    public function goToIndex(): Response
    {
        return $this->redirectToRoute('app_home'); 
    }
}

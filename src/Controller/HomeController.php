<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(): Response{
        return new Response("Bonjour mes étudiants");
    }
     #[Route(path:'/hello', name: 'hello')]
    public function hello(): Response{
        return new Response("hello the fool");
    }
     #[Route(path:'/contact/{tel}', name: 'contact')]
    public function contact($tel): Response{
        return $this->render(view:'home/contact.html.twig',parameters:['telephone'=>$tel]);
    }
     #[Route(path:'/show', name: 'show')]
    public function show(): Response{
        return new Response("Bienvenue");
    }
    #[Route(path:'/apropos/{prop}', name: 'apropos')]
    public function afficher($prop): Response{
        return $this->render(view:'home/apropos.html.twig',parameters:['propos'=>$prop]);
    }
}

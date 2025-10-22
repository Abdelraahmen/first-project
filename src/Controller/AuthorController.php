<?php

namespace App\Controller;

use App\Repository\AuthorRepository;
use App\Entity\Author;
use App\Form\AuthorformType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }

    #[Route('/show/{name}', name:'showAuthor')]
    public function showAuthor($name): Response
    {
        return $this->render('author/show.html.twig', ['nom' => $name]);
    }

    #[Route('/list', name:'listauthor')]
    public function listAuthors(): Response
    {
        $authors = [
            [
                'id' => 1,
                'picture' => 'assets/images/Victor_Hugo.jpg',
                'username' => 'Victor Hugo',
                'email' => 'victor.hugo@gmail.com',
                'nb_books' => 100
            ],
            [
                'id' => 2,
                'picture' => 'assets/images/Shakespeare.jpg',
                'username' => 'William Shakespeare',
                'email' => 'william.shakespeare@gmail.com',
                'nb_books' => 200
            ],
            [
                'id' => 3,
                'picture' => 'assets/images/taha-hussein.jpg',
                'username' => 'Taha Hussein',
                'email' => 'taha.hussein@gmail.com',
                'nb_books' => 300
            ],
        ];
        
        return $this->render('author/list.html.twig', [
            'authors' => $authors,
        ]);
    }

    #[Route('/author/{id}', name: 'author_details', requirements: ['id' => '\d+'])]
    public function authorDetails(int $id): Response
    {
        $authors = [
            [
                'id' => 1,
                'picture' => 'assets/images/Victor_Hugo.jpg',
                'username' => 'Victor Hugo',
                'email' => 'victor.hugo@gmail.com',
                'nb_books' => 100
            ],
            [
                'id' => 2,
                'picture' => 'assets/images/Shakespeare.jpg',
                'username' => 'William Shakespeare',
                'email' => 'william.shakespeare@gmail.com',
                'nb_books' => 200
            ],
            [
                'id' => 3,
                'picture' => 'assets/images/taha-hussein.jpg',
                'username' => 'Taha Hussein',
                'email' => 'taha.hussein@gmail.com',
                'nb_books' => 300
            ],
        ];

        $author = null;
        foreach ($authors as $a) {
            if ($a['id'] === $id) {
                $author = $a;
                break;
            }
        }

        if (!$author) {
            throw $this->createNotFoundException('Auteur non trouvé');
        }

        return $this->render('author/showAuthor.html.twig', [
            'author' => $author,
        ]);
    }

    #[Route('/showALL', name:'showALL')]
    public function showALL(AuthorRepository $repo): Response
    {
        $authors = $repo->findALL();
        return $this->render('author/showALL.html.twig', ['list' => $authors]);
    }

    #[Route('/author/form', name:'author_form')]
    public function authorForm(Request $request, EntityManagerInterface $em): Response
    {
        $author = new Author();
        $form = $this->createForm(AuthorformType::class, $author);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($author);
            $em->flush();

            return $this->redirectToRoute('showALL');
        }

        return $this->render('author/formAuthor.twig', [
            'form' => $form->createView(),
        ]);
    }

    // 🆕 Edit author
    #[Route('/author/edit/{id}', name: 'author_edit', requirements: ['id' => '\d+'])]
    public function edit(Request $request, EntityManagerInterface $em, AuthorRepository $repo, int $id): Response
    {
        $author = $repo->find($id);
        if (!$author) {
            throw $this->createNotFoundException('Auteur non trouvé');
        }

        $form = $this->createForm(AuthorformType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('showALL');
        }

        return $this->render('author/formAuthor.twig', [
            'form' => $form->createView(),
        ]);
    }

    // 🆕 Delete author
    #[Route('/author/delete/{id}', name: 'author_delete', requirements: ['id' => '\d+'])]
    public function delete(EntityManagerInterface $em, AuthorRepository $repo, int $id): Response
    {
        $author = $repo->find($id);
        if (!$author) {
            throw $this->createNotFoundException('Auteur non trouvé');
        }

        $em->remove($author);
        $em->flush();

        return $this->redirectToRoute('showALL');
    }
}

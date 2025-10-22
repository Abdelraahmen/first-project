<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/book')]
class BookController extends AbstractController
{
    // List all books
    #[Route('/', name: 'book_list')]
    public function list(BookRepository $repo): Response
    {
        $books = $repo->findAll();
        return $this->render('book/list.html.twig', ['books' => $books]);
    }

    // Add a new book
    #[Route('/new', name: 'book_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($book);
            $em->flush();

            $this->addFlash('success', 'Book added successfully!');
            return $this->redirectToRoute('book_list');
        }

        return $this->render('book/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Edit an existing book
    #[Route('/edit/{id}', name: 'book_edit', requirements: ['id' => '\d+'])]
    public function edit(Request $request, EntityManagerInterface $em, BookRepository $repo, int $id): Response
    {
        $book = $repo->find($id);
        if (!$book) {
            throw $this->createNotFoundException('Book not found');
        }

        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Book updated successfully!');
            return $this->redirectToRoute('book_list');
        }

        return $this->render('book/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Delete a book
    #[Route('/delete/{id}', name: 'book_delete', requirements: ['id' => '\d+'])]
    public function delete(EntityManagerInterface $em, BookRepository $repo, int $id): Response
    {
        $book = $repo->find($id);
        if (!$book) {
            throw $this->createNotFoundException('Book not found');
        }

        $em->remove($book);
        $em->flush();

        $this->addFlash('success', 'Book deleted successfully!');
        return $this->redirectToRoute('book_list');
    }

    // Show book details
    #[Route('/show/{id}', name: 'book_show', requirements: ['id' => '\d+'])]
    public function show(BookRepository $repo, int $id): Response
    {
        $book = $repo->find($id);
        if (!$book) {
            throw $this->createNotFoundException('Book not found');
        }

        return $this->render('book/show.html.twig', ['book' => $book]);
    }
}

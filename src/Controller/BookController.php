<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\AnnotationsasRest;
use FOS\RestBundle\Controller\FOSRestController;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;

/**
 *BookController.
 *@Route("/api",name="api_")
 */
class BookController extends AbstractFOSRestController
{
    /**
     * List of books.
     *
     * @Rest\Get("/books")
     * @OA\Tag(name="Books")
     */
    public function bookWithContributor(BookRepository $bookRepository)
    {
        return $bookRepository->bookWithContributor();
    }

    /**
     * Add a new  book.
     * @Rest\Post("/books")
     * @OA\RequestBody(
     *     required=true,
     *     @Model(type=Book::class, groups={"bookBody"})
     * )
     * @OA\Tag(name="Books")
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     */
    public function addNewBook(BookRepository $bookRepository,Request $request)
    {
        $params =  json_decode($request->getContent());

        return $bookRepository->newBook($params);
    }

    /**
     * Update a book.
     * @Rest\Put("/books")
     * @OA\RequestBody(
     *     required=true,
     *     @Model(type=Book::class, groups={"bookBody","bookId"})
     * )
     * @OA\Tag(name="Books")
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     */
    public function setBook(BookRepository $bookRepository,Request $request)
    {
        $params =  json_decode($request->getContent());

        return $bookRepository->updateBook($params);
    }

    /**
     * Delete a book.
     * @Rest\Delete("/books/{id}")
     * @OA\Tag(name="Books")
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     */
    public function deleteBook(BookRepository $bookRepository,Book $book)
    {
        return $bookRepository->deleteBook($book);
    }

    /**
     * Search a book with book name or contributor name.
     * @Rest\Post("/search_book")
     * @OA\RequestBody(
     *  @OA\MediaType(
     *      mediaType="application/json",
     *      @OA\Schema(
     *          required={"book_name"},
     *          @OA\Property(property="book_name", type="string"),
     *          @OA\Property(property="contributor_name", type="string")
     *      )
     *  )
     *)
     * @OA\Tag(name="Search")
     */
    public function searchBook(BookRepository $bookRepository,Request $request)
    {
        $params =  json_decode($request->getContent());

        return $bookRepository->searchBook($params);
    }
}

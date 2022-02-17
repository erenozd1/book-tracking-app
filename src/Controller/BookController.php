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
     * List the book list.
     *
     *
     * @Rest\Get("/books")
     * @OA\Response(
     *     response=200,
     *     description="Returns list of books",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Book::class, groups={"full"}))
     *     )
     * )
     * @OA\Tag(name="Books")
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     */
    public function bookWithContributor(BookRepository $bookRepository, Request $request)
    {   $data = $request->getContent();
        //return $data;
        $data = $bookRepository->book_with_contributor();
        $view = $this->view($data, 200);
       // return  $this->getUser();

        return $view;
    }

    /**
     * Add new book.
     *
     * @Rest\Post("/books")
     * @OA\Response(
     *     response=200,
     *     description="Returns the rewards of an user",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Book::class, groups={"full"}))
     *     )
     * )
     *
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
     * Add new book.
     *
     * @Rest\Post("/books")
     * @OA\Response(
     *     response=200,
     *     description="Returns the rewards of an user",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Book::class, groups={"full"}))
     *     )
     * )
     *
     * @OA\RequestBody(
     *     required=true,
     *     @Model(type=Book::class, groups={"bookBody"})
     * )
     * @OA\Tag(name="Books")
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     */
    public function setBook(BookRepository $bookRepository,Request $request)
    {
        $params =  json_decode($request->getContent());

        return $bookRepository->newBook($params);
    }
}

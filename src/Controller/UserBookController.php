<?php

namespace App\Controller;

use App\Entity\UserBook;
use App\Repository\BookRepository;
use App\Repository\UserBookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\AnnotationsasRest;
use FOS\RestBundle\Controller\FOSRestController;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * UserBookController
 * @Route ("/api", name="api_")
 */
class UserBookController extends AbstractFOSRestController
{
    /**
     * List of user's read status of books
     * @Rest\Get ("/userBook")
     * @OA\Tag(name="UsersBooks")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function userBooks(UserBookRepository $userBookRepository)
    {
        return $userBookRepository->getUserBookWithNames();
    }

    /**
     * @Rest\Post("/userBook")
     * @OA\RequestBody(
     *     required=true,
     *     @Model(type=UserBook::class, groups={"UserBookBody"})
     * )
     * @OA\Tag(name="UsersBooks")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function addNewBookUser(UserBookRepository $userBookRepository, Request $request)
    {
        $params = json_decode($request->getContent(), true);
        $params['user_id']= $this->getUser()->getId();

        return $userBookRepository->addUserBook($params);
    }
}

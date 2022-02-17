<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\BookRepository;
use App\Repository\UserBookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserBookController extends AbstractController
{
    /**
     * @Route("/user/book", name="user_book")
     */
    public function index(UserBookRepository $userBookRepository): Response
    {

        $result = $userBookRepository->getUserBookWithNames();
        dump($result);
        exit;
        return $this->render('user_book/index.html.twig', [
            'controller_name' => 'UserBookController',
        ]);
    }

    /**
     * @Route ("/add_new_book_user/{book_id}/{user_id}/{is_readed}", name="add_new_book_user")
     * @param UserBookRepository $userBookRepository
     * @param $book_id
     * @param $user_id
     * @param $is_readed
     * @return void
     */
    public function addNewBookUser(UserBookRepository $userBookRepository, $book_id, $user_id, $is_readed)
    {
        $params['book_id'] = $book_id;
        $params['user_id'] = $user_id;
        $params['is_readed'] = $is_readed;
        $result = $userBookRepository->addUserBook($params);
        dump($result);
        exit;

    }
}

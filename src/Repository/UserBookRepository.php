<?php

namespace App\Repository;

use App\Entity\Book;
use App\Entity\User;
use App\Entity\UserBook;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserBook|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserBook|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserBook[]    findAll()
 * @method UserBook[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserBookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserBook::class);
    }

    // /**
    //  * @return UserBook[] Returns an array of UserBook objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserBook
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function getUserBookWithNames()
    {
        return $this->createQueryBuilder('u')
            ->select("u.is_readed")
            ->addSelect("c.name")
            ->leftJoin("u.book","c")
            ->addSelect("d.username")
            ->leftJoin("u.usr","d")
            ->getQuery()
            ->getArrayResult();
    }

    /**
     * @param $params
     * @return string[]
     */
    public function addUserBook($params = array())
    {
        $result = ['status' => 'success','message'=>'İşlem Başarılı'];

        try {
            $em = $this->getEntityManager();
            $newUserBook = new UserBook();
            $newUserBook
                ->setBook($em->find(Book::class, $params['book_id']));
            $newUserBook
                ->setUsr($em->find(User::class, $params['user_id']));
            $newUserBook
                ->setIsReaded($params['is_readed']);;
            $em->persist($newUserBook);
            $em->flush();

        }catch (\Exception $exception){
            $result['status'] = false;
            $result['message']= $exception->getMessage();
        }
        return $result;

    }

    /**
     * @return string[]
     */
    public function setUserBook()
    {
        $result = ['status' => 'success','message'=>'İşlem Başarılı'];

        try {
            $em = $this->getEntityManager();
            $userbook = $em->find(Book::class, $params['book_id']);
            $category
                ->setName("biyografi");
            $em->persist($category);
            $em->flush();

        }catch (\Exception $exception){
            $result['status'] = false;
            $result['message']= $exception->getMessage();
        }
        return $result;
    }

}

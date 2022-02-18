<?php

namespace App\Repository;

use App\Entity\Book;
use App\Entity\UserBook;
use App\Entity\User;
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

    /**
     * @return float|int|array|string
     */
    public function getUserBookWithNames()
    {
        return $this->createQueryBuilder('u')
            ->select("u.is_readied")
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
    public function addUserBook($params)
    {
        $result = ['status' => 'success','message'=>'İşlem Başarılı'];

        try {
            $em = $this->getEntityManager();
            $query = $em->createQuery(
                'SELECT ub.book_id, ub.usr_id, ub.is_readied,ub.id
                FROM App\Entity\UserBook ub
                WHERE ub.usr_id = :usr_id and ub.book_id = :book_id
           ')
            ->setParameter('usr_id', $params->{'usr_id'})
            ->setParameter('book_id', $params->{'book_id'});

            $tmp_result = $query->getResult();
            if(count($tmp_result) != 0){
                $newUserBook = $em->find(UserBook::class, $tmp_result[0]['id']);
            }else{
                $newUserBook = new UserBook();
                $newUserBook
                    ->setBook($em->find(Book::class, $params->{'book_id'}));
                $newUserBook
                    ->setUsr($em->find(User::class, $params->{'usr_id'}));
            }

            $newUserBook
                ->setIsReadied($params->{'is_readied'});


            $em->persist($newUserBook);
            $em->flush();
        }catch (\Exception $exception){
            $result['status'] = false;
            $result['message']= $exception->getMessage();
        }
        return $result;
    }
}

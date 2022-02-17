<?php

namespace App\Repository;

use App\Entity\Book;
use App\Entity\Contributor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    // /**
    //  * @return Book[] Returns an array of Book objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */


    public function get()
    {
        return $this->createQueryBuilder('b')
            ->getQuery()
            ->getArrayResult()
        ;
    }

    public function book_with_contributor()
    {
        return $this->createQueryBuilder("b")
            ->select("b.name as book_name")
            ->addSelect("c.name as contributor_name")
            ->addSelect("c.surname as contributor_surname")
            ->leftJoin("b.contributor","c")
            ->getQuery()
            ->getArrayResult();
    }

    /**
     * @return string[]
     */
    public function newBook($params)
    {
        $result = ['status' => 'success','message'=>'İşlem Başarılı'];

        try {
            $em = $this->getEntityManager();
            $newBook = new Book();
            $newBook
                ->setName($params->{'name'});
            $newBook
                ->setAmount($params->{'amount'});
            $newBook
                ->setPrice($params->{'price'});
            $newBook
                ->setContributor($em->find(Contributor::class, $params->{'contributor_id'}));
            $em->persist($newBook);
            $em->flush();

        }catch (\Exception $exception){
            $result['status'] = false;
            $result['message']= $exception->getMessage();
        }
        return $result;
    }

}

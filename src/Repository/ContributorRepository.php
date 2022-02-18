<?php

namespace App\Repository;

use App\Entity\Contributor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use JMS\Serializer\Handler\StdClassHandler;
use phpDocumentor\Reflection\Types\Object_;

/**
 * @method Contributor|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contributor|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contributor[]    findAll()
 * @method Contributor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContributorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contributor::class);
    }

    // /**
    //  * @return Contributor[] Returns an array of Contributor objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */


    public function get()
    {
        return $this->createQueryBuilder('c')
            ->getQuery()
            ->getArrayResult()
        ;
    }

    /**
     * @return string[]
     */
    public function newContributor($params)
    {
        $result = ['status' => 'success','message'=>'İşlem Başarılı'];

        try {
            $em = $this->getEntityManager();
            $newContributor = new Contributor();
            $newContributor->setName($params->{'name'});
            $newContributor->setSurname($params->{'surname'});
            $em->persist($newContributor);
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
    public function updateContributor($params)
    {
        $result = ['status' => 'success','message'=>'İşlem Başarılı'];

        try {
            $em = $this->getEntityManager();
            $setContributor = $em->find(Contributor::class, $params->{'id'});
            $setContributor->setName($params->{'name'});
            $setContributor->setSurname($params->{'surname'});
            $em->persist($setContributor);
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
    public function deleteContributor($params)
    {
        $result = ['status' => 'success','message'=>'İşlem Başarılı'];

        try {
            $em = $this->getEntityManager();
            $contributor = $em->find(Contributor::class, $params->{'id'});
            $em->remove($contributor);
            $em->flush();

        }catch (\Exception $exception){
            $result['status'] = false;
            $result['message']= $exception->getMessage();
        }
        return $result;
    }

}

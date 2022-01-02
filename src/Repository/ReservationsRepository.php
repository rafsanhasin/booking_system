<?php

namespace App\Repository;

use App\Entity\Reservations;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Reservations|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservations|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservations[]    findAll()
 * @method Reservations[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservations::class);
    }

    /**
     * @return mixed
     */
    public function getAllWithRelations() {
        return $this->createQueryBuilder('res')
            ->select('res.id')
            ->select('res.fromDate')
            ->addSelect('res.toDate')
            ->addSelect('res.toDate')
            ->addSelect('res.totalPrice')
            ->addSelect('res.duration')
            ->addSelect('c.name AS customerName')
            ->addSelect('r.name AS roomName')
            ->innerJoin('res.customer', 'c')
            ->innerJoin('res.room', 'r')
            ->orderBy('res.fromDate', 'DESC')
            ->getQuery()
            ->getResult();
    }
}

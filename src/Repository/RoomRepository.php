<?php

namespace App\Repository;

use App\Entity\Room;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Room|null find($id, $lockMode = null, $lockVersion = null)
 * @method Room|null findOneBy(array $criteria, array $orderBy = null)
 * @method Room[]    findAll()
 * @method Room[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoomRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Room::class);
    }

    /**
     * @param $from
     * @param $to
     * @return mixed
     */
    public function getAvailableRooms($from, $to) {
        $qb = $this->createQueryBuilder('r');

        $reservedRooms = $qb->select('r.id')
            ->leftJoin('r.reservation', 'res')
            ->where('res.fromDate BETWEEN :from AND :to')
            ->orWhere('res.toDate BETWEEN :from AND :to')
            ->setParameter('from', $from)
            ->setParameter('to', $to);

        $qb = $this->createQueryBuilder('r1');
        $availableRooms = $qb->select('r1')
            ->where('r1.isAvailable = 1')
            ->andWhere($qb->expr()->notIn('r1.id', $reservedRooms->getDQL()))
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->getQuery()
            ->getResult();

        return $availableRooms;
    }
}

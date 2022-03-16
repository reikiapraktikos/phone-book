<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Record;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Record|null find($id, $lockMode = null, $lockVersion = null)
 * @method Record|null findOneBy(array $criteria, array $orderBy = null)
 * @method Record[]    findAll()
 * @method Record[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class RecordRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Record::class);
    }

    public function add(Record $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function remove(Record $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @param User $user
     * @param int $id
     * @return Record|null
     * @throws NonUniqueResultException
     */
    public function findOneByOwnerUserAndId(User $user, int $id): ?Record
    {
        return $this
            ->createQueryBuilder('record')
            ->join('record.recordUsers', 'recordUsers')
            ->where('recordUsers.user = :user AND record.id = :id AND recordUsers.isOwner = true')
            ->setParameter('user', $user)
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param User $user
     * @return Record[]
     */
    public function findAllByUser(User $user): array
    {
        return $this
            ->createQueryBuilder('record')
            ->join('record.recordUsers', 'recordUsers')
            ->where('recordUsers.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }
}

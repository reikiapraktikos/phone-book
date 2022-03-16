<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Record;
use App\Entity\RecordUser;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RecordUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method RecordUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method RecordUser[]    findAll()
 * @method RecordUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class RecordUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RecordUser::class);
    }

    public function add(RecordUser $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function remove(RecordUser $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @param Record $record
     * @param User $user
     * @return RecordUser|null
     * @throws NonUniqueResultException
     */
    public function findOneByRecordAndNonOwnerUser(Record $record, User $user): ?RecordUser
    {
        return $this
            ->createQueryBuilder('recordUser')
            ->where(
                'recordUser.user = :user AND recordUser.record = :record AND recordUser.isOwner = false'
            )
            ->setParameter('user', $user)
            ->setParameter('record', $record)
            ->getQuery()
            ->getOneOrNullResult();
    }
}

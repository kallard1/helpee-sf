<?php

declare(strict_types=1);

/**
 * This file is a part of Helpee.
 *
 * @author  Kevin Allard <contact@allard-kevin.fr>
 *
 * @license 2018-2019 - Helpee
 */

namespace App\Repository\Ad;

use App\Entity\Ad\Message\Thread;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class ThreadRepository.
 */
class ThreadRepository extends ServiceEntityRepository
{
    /**
     * ThreadRepository constructor.
     *
     * @param \Doctrine\Common\Persistence\ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Thread::class);
    }

    /**
     * @param \App\Entity\User $user
     *
     * @return mixed
     */
    public function getUserThreads(User $user)
    {
        return $this->createQueryBuilder('t')
            ->leftJoin('t.participants', 'p')
            ->addSelect('p')
            ->leftJoin('t.messages', 'm')
            ->addSelect('m')
            ->andWhere('p.id = :user')
            ->setParameter('user', $user)
            ->orderBy('m.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param \App\Entity\Ad\Message\Thread $thread
     *
     * @return mixed
     */
    public function getThread(Thread $thread)
    {
        return $this->createQueryBuilder('t')
            ->leftJoin('t.participants', 'p')
            ->addSelect('p')
            ->leftJoin('t.messages', 'm')
            ->addSelect('m')
            ->leftJoin('t.ad', 'a')
            ->addSelect('a')
            ->andWhere('t.id = :thread')
            ->setParameter('thread', $thread)
            ->orderBy('m.createdAt', 'ASC')
            ->getQuery()
            ->getResult();
    }
}

<?php

declare(strict_types=1);

/**
 * This file is a part of Helpee
 * @author  Kevin Allard <contact@allard-kevin.fr>
 * @license 2018
 */

namespace App\Repository;

use App\Entity\Community;
use App\Entity\User;
use App\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class CommunityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Community::class);
    }

    public function getActiveCommunities(int $page = 1): Paginator
    {
        $qb = $this->createQueryBuilder('c')
            ->andWhere('c.enabled = true')
            ->orderBy('c.name', 'ASC');

        return (new Paginator($qb))->paginate($page);
    }

    public function userInCommunity(Community $community, User $user)
    {
        $qb = $this->createQueryBuilder('c')
            ->select('COUNT(c)')
            ->leftJoin('c.members', 'm')
            ->andWhere('c.id = :community')
            ->setParameter('community', $community)
            ->andWhere('m.id = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();

        return $qb[0][1] > 0;
    }
}

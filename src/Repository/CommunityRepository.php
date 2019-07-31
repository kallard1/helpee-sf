<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Community;
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
}

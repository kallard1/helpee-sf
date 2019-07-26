<?php

declare(strict_types=1);

namespace App\Repository\Ad;

use App\Entity\Ad\Ad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;

class AdRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ad::class);
    }

    public function sumUEV()
    {
        try {
            return $this->createQueryBuilder('c')
                ->select('SUM(c.uev)')
                ->andWhere('c.enabled = true')
                ->getQuery()
                ->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            die($e);
        }
    }

    public function getActiveAds(int $limit = 0)
    {
        $request = $this->createQueryBuilder('ads')
            ->andWhere('ads.enabled = true')
            ->orderBy('ads.createdAt', 'DESC');

        if ($limit > 0) {
            return $request
                ->setMaxResults($limit)
                ->getQuery()
                ->getResult();
        }

        return $request->getQuery()->getResult();
    }
}

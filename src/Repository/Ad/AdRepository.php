<?php

declare(strict_types=1);

namespace App\Repository\Ad;

use App\Entity\Ad\Ad;
use App\Entity\Community;
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

    public function getActiveAds(Community $community, int $limit = 0)
    {
        $request = $this->createQueryBuilder('ads')
            ->andWhere('ads.enabled = true')
            ->andWhere('ads.community = :community')
            ->setParameter('community', $community->getId())
            ->orderBy('ads.createdAt', 'DESC');

        if ($limit > 0) {
            return $request
                ->setMaxResults($limit)
                ->getQuery()
                ->getResult();
        }

        return $request->getQuery()->getResult();
    }

    public function findAds($category, $keywords, $city)
    {
        var_dump($category, $keywords, $city);

        $query = $this->sanitizeSearchQuery($keywords);
        $query = $this->extractSearchTerms($query);

        $request = $this->createQueryBuilder('ads')
            ->leftJoin('ads.category', 'adc')
            ->addSelect('adc');


        if ($category != "" || $category != null) {
            $request->andWhere('adc.slug = :slug')
                ->setParameter('slug', $category);
        }

        if ($keywords != "" || $keywords != null) {
            foreach ($query as $keyword) {
                $request->andWhere('ads.title LIKE :keyword')
                    ->setParameter('keyword', '%' . $keyword . '%');
            }
        }

        if ($city != "" || $city != null) {
            $request
                ->leftJoin('ads.community', 'c')
                ->addSelect('c')
                ->leftJoin('c.city', 'city')
                ->addSelect('city')
                ->andWhere('city.id = :id')
                ->setParameter('id', $city);
        }

        dump($request->getQuery());

        return $request->getQuery()
            ->getResult();
    }

    /**
     * Removes all non-alphanumeric characters except whitespaces.
     *
     * @param string $query
     *
     * @return string
     */
    private function sanitizeSearchQuery(string $query): string
    {
        return trim(preg_replace('/[[:space:]]+/', ' ', $query));
    }

    /**
     * Splits the search query into terms and removes the ones which are irrelevant.
     *
     * @param string $searchQuery
     *
     * @return array
     */
    private function extractSearchTerms(string $searchQuery): array
    {
        $terms = array_unique(explode(' ', $searchQuery));

        return array_filter($terms, function ($term) {
            return 2 <= mb_strlen($term);
        });
    }
}

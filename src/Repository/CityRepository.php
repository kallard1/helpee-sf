<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\City;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class CityRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, City::class);
    }

    public function findBySearchQuery(string $rawQuery = null, int $limit = City::NUM_ITEMS)
    {
        $query = $this->sanitizeSearchQuery($rawQuery);

        return $this->createQueryBuilder('c')
            ->orWhere('lower(c.name) LIKE lower(:query)')
            ->orWhere('lower(c.zip_code) LIKE lower(:query)')
            ->setParameter('query', '%' . $query . '%')
            ->orderBy('c.name', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
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

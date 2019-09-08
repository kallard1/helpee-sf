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

use App\Entity\Ad\Ad;
use App\Entity\Community;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;

/**
 * Class AdRepository.
 */
class AdRepository extends ServiceEntityRepository
{
    /**
     * AdRepository constructor.
     *
     * @param \Doctrine\Common\Persistence\ManagerRegistry $registry registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ad::class);
    }

    /**
     * Return UEV sum.
     *
     * @return mixed
     */
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

    /**
     * Get active Ads.
     *
     * @param \App\Entity\Community $community community
     * @param int                   $limit     limit
     *
     * @return mixed
     */
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

    /**
     * Find Ads.
     *
     * @param array $terms Terms
     *
     * @return mixed
     */
    public function findAds(array $terms = [])
    {
        $request = $this->createQueryBuilder('ads')
            ->leftJoin('ads.category', 'adc')
            ->addSelect('adc')
            ->leftJoin('ads.community', 'c')
            ->addSelect('c')
            ->leftJoin('c.city', 'city')
            ->addSelect('city')
            ->andWhere('ads.enabled = true');

        if (\array_key_exists('category', $terms) && ('' != $terms['category'] || null != $terms['category'])) {
            $request->andWhere('adc.slug = :slug')
                ->setParameter('slug', $terms['category']);
        }

        if (\array_key_exists('keywords', $terms) && ('' != $terms['keywords'] || null != $terms['keywords'])) {
            $query = $this->sanitizeSearchQuery($terms['keywords']);
            $query = $this->extractSearchTerms($query);

            foreach ($query as $keyword) {
                $request->andWhere('lower(ads.title) LIKE lower(:keyword)')
                    ->setParameter('keyword', '%'.$keyword.'%');
            }
        }

        if (\array_key_exists('city', $terms) && ('' != $terms['city'] || null != $terms['city'])) {
            $request
                ->andWhere('city.id = :id')
                ->setParameter('id', $terms['city']);
        }

        return $request->getQuery()
            ->getResult();
    }

    /**
     * Removes all non-alphanumeric characters except whitespaces.
     *
     * @param string $query query
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
     * @param string $searchQuery search terms query
     *
     * @return array
     */
    private function extractSearchTerms(string $searchQuery): array
    {
        $terms = array_unique(explode(' ', $searchQuery));

        return array_filter(
            $terms,
            function ($term) {
                return 2 <= mb_strlen($term);
            }
        );
    }
}

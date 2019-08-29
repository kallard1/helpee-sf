<?php

declare(strict_types=1);

namespace App\Controller\Traits;

use App\Entity\Ad\Category;
use Symfony\Component\Cache\Adapter\RedisAdapter;

/**
 * Trait populateCategories.
 */
trait RedisPopulateTrait
{
    private $client;

    /**
     * Set categories into Redis.
     *
     * @return void
     */
    private function setRedisCategories()
    {
        $entityManager = $this->getDoctrine()->getManager();

        if (null === $this->client->get('ads.categories') || false === $this->client->get('ads.categories')) {
            $categories = $entityManager->getRepository(Category::class)->findAll();

            $data = [];
            foreach ($categories as $category) {
                $data[] = [
                    'id' => $category->getId(),
                    'label' => $category->getLabel(),
                    'slug' => $category->getSlug(),
                    'level' => $category->getLevel(),
                ];
            }

            $this->client->set('ads.categories', json_encode($data));
        }
    }

    /**
     * Get categories into Redis cache.
     *
     * @return mixed
     */
    public function getRedisCategories()
    {
        $this->client = RedisAdapter::createConnection(
            $this->getParameter('redis_url')
        );

        if (null === $this->client->get('ads.categories') || false === $this->client->get('ads.categories')) {
            $this->setRedisCategories();
        }

        return json_decode($this->client->get('ads.categories'));
    }
}

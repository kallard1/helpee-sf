<?php

declare(strict_types=1);

/**
 * This file is a part of Helpee.
 *
 * @author  Kevin Allard <contact@allard-kevin.fr>
 *
 * @license 2018-2019 - Helpee
 */

namespace App\Elasticsearch;

use App\Entity\City;
use App\Repository\CityRepository;
use Elastica\Client;
use Elastica\Document;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CityIndexer
{
    private $client;
    private $cityRepository;
    private $router;

    /**
     * CityIndexer constructor.
     *
     * @param \Elastica\Client                                           $client
     * @param \App\Repository\CityRepository                             $cityRepository
     * @param \Symfony\Component\Routing\Generator\UrlGeneratorInterface $router
     */
    public function __construct(Client $client, CityRepository $cityRepository, UrlGeneratorInterface $router)
    {
        $this->client = $client;
        $this->cityRepository = $cityRepository;
        $this->router = $router;
    }

    /**
     * @param \App\Entity\City $city
     *
     * @return \Elastica\Document
     */
    public function buildDocument(City $city)
    {
        return new Document(
            $city->getId(),
            [
                'id' => $city->getId(),
                'department_id' => $city->getDepartment(),
                'insee_code' => $city->getInseeCode(),
                'zip_code' => $city->getZipCode(),
                'name' => $city->getName(),
                'slug' => $city->getSlug(),
                'latitude' => $city->getLatitude(),
                'longitude' => $city->getLongitude(),
                'created_at' => $city->getCreatedAt(),
                'updated_at' => $city->getUpdatedAt(),
            ],
            'city'
        );
    }

    /**
     * @param $indexName
     */
    public function indexAllDocuments($indexName)
    {
        $allCities = $this->cityRepository->findAll();
        $index = $this->client->getIndex($indexName);

        $documents = [];

        foreach ($allCities as $city) {
            $documents[] = $this->buildDocument($city);
        }

        $index->addDocuments($documents);
        $index->refresh();
    }
}

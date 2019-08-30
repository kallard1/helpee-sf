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

use Elastica\Client;
use Symfony\Component\Yaml\Yaml;

/**
 * Class IndexBuilder.
 */
class IndexBuilder
{
    private $client;

    /**
     * IndexBuilder constructor.
     *
     * @param \Elastica\Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @return \Elastica\Index
     */
    public function create()
    {
        $index = $this->client->getIndex('city');

        $settings = Yaml::parse(
            file_get_contents(
                __DIR__.'/../../config/elasticasearch_index_city.yaml'
            )
        );

        $index->create($settings, true);

        return $index;
    }
}

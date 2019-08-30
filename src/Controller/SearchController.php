<?php

declare(strict_types=1);

/**
 * This file is a part of Helpee.
 *
 * @author  Kevin Allard <contact@allard-kevin.fr>
 *
 * @license 2018-2019 - Helpee
 */

namespace App\Controller;

use App\Repository\Ad\AdRepository;
use App\Repository\CityRepository;
use Elastica\Client;
use Elastica\Query;
use Elastica\Query\BoolQuery;
use Elastica\Query\MultiMatch;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SearchController.
 *
 * @Route("/search", name="search")
 */
class SearchController extends AbstractController
{
    /**
     * @Route("/city", methods={"GET"}, name="_city")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Elastica\Client                          $client
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function searchCity(Request $request, Client $client): Response
    {
        $query = $request->query->get('q', '');
        $limit = $request->query->get('page', 10);

        $match = new MultiMatch();

        $match->setQuery($query);
        $match->setFields(["name^2", "zip_code^2"]);

        $bool = new BoolQuery();
        $bool->addMust($match);

        $elasticaQuery = new Query($bool);
        $elasticaQuery->setSize($limit);

        $foundCities = $client->getIndex('city')->search($elasticaQuery);
        $results = [];

        foreach ($foundCities as $city) {
            $results[] = $city->getSource();
        }

        return $this->json($results);
    }

    /**
     * Search Ads.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Repository\Ad\AdRepository           $adRepository
     *
     * @Route("/ad", methods={"GET"}, name="_ad")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function searchAds(Request $request, AdRepository $adRepository): Response
    {
        $terms = [];

        if ($request->query->get('category')) {
            $terms['category'] = $request->query->get('category');
        }

        if ($request->query->get('keywords')) {
            $terms['keywords'] = $request->query->get('keywords');
        }

        if ($request->query->get('city')) {
            $terms['city'] = $request->query->get('city');
        }

        return $this->render('search/ad/result.html.twig', ['ads' => $adRepository->findAds($terms)]);
    }
}

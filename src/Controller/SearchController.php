<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\City;
use App\Repository\CityRepository;
use Elastica\Suggest;
use FOS\ElasticaBundle\Manager\RepositoryManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SearchController
 *
 * @package App\Controller
 * @Route("/search", name="search")
 */
class SearchController extends AbstractController
{
    /**
     * @Route("/city", methods={"GET"}, name="_city")
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Repository\CityRepository            $city
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function searchCity(Request $request, CityRepository $city): Response
    {
        $query = $request->query->get('q', '');
        $limit = $request->query->get('page', 10);


        $foundCities = $city->findBySearchQuery($query, (int) $limit);

        return $this->json($foundCities);
    }
}

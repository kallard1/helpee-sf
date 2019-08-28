<?php

declare(strict_types=1);

/**
 * This file is a part of Helpee
 *
 * @author  Kevin Allard <contact@allard-kevin.fr>
 * @license 2018
 */

namespace App\Controller;

use App\Entity\City;
use App\Repository\Ad\AdRepository;
use App\Repository\CityRepository;
use FOS\ElasticaBundle\Manager\RepositoryManagerInterface;
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
     * @param \App\Repository\CityRepository            $city
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function searchCity(Request $request, CityRepository $city): Response
    {
        $query = $request->query->get('q', '');
        $limit = $request->query->get('page', 10);

        $foundCities = $city->findBySearchQuery($query, (int)$limit);

        return $this->json($foundCities);
    }

    /**
     * Search Ads.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request      Request.
     * @param \App\Repository\Ad\AdRepository           $adRepository Ad Repository.
     *
     * @Route("/ad", methods={"GET"}, name="_ad")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function searchAds(Request $request, AdRepository $adRepository): Response
    {
        $category = $request->query->get('category');
        $keywords = $request->query->get('keywords');
        $city = $request->query->get('city');

        dump($category, $keywords, $city);
        return $this->render(
            'search/ad/result.html.twig', [
                'ads' => $adRepository->findAds($category, $keywords, $city),
            ]
        );
    }

//    /**
//     * @Route("/city", methods={"GET"}, name="_city")
//     * @param \Symfony\Component\HttpFoundation\Request              $request
//     *
//     * @param \FOS\ElasticaBundle\Manager\RepositoryManagerInterface $finder
//     *
//     * @return \Symfony\Component\HttpFoundation\Response
//     */
//    public function searchCity(Request $request, RepositoryManagerInterface $finder): Response
//    {
//        $query = $request->query->get('q', '');
//        $limit = $request->query->get('page', 10);
//
//        $repository = $finder->getRepository(City::class);
//        return $this->json("");
//    }
}

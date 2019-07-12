<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Ad\Ad;
use App\Entity\Ad\Category;
use App\Entity\Community;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $client = RedisAdapter::createConnection(
            $this->getParameter('redis_url')
        );

        if ($client->get('ads.categories') === null) {
            $categories = $entityManager->getRepository(Category::class)->findAll();

            $data = [];
            foreach ($categories as $category) {
                $data[] = [
                    "id" => $category->getId(),
                    "label" => $category->getLabel(),
                    "slug" => $category->getSlug(),
                    "level" => $category->getLevel(),
                ];
            }

            $client->set('ads.categories', json_encode($data));
        }

        $users = $entityManager->getRepository(User::class)->findAll();
        $communities = $entityManager->getRepository(Community::class)->findAll();
        $sum_uev = $entityManager->getRepository(Ad::class)->sumUEV();

        if ($sum_uev[1] === NULL) {
            $sum_uev = 0;
        } else {
            $sum_uev = $sum_uev[1];
        }

        return $this->render('homepage/index.html.twig', [
            'categories' => json_decode($client->get('ads.categories')),
            'count_communities' => count($communities),
            'count_users' => count($users),
            'count_uev' => $sum_uev,
        ]);
    }
}

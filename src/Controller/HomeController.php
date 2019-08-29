<?php

declare(strict_types=1);

/**
 * This file is a part of Helpee
 *
 * @author  Kevin Allard <contact@allard-kevin.fr>
 * @license 2018
 */

namespace App\Controller;

use App\Controller\Traits\RedisPopulate;
use App\Entity\Ad\Ad;
use App\Entity\Community;
use App\Entity\User;
use App\Form\AdType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomeController
 *
 * @package App\Controller
 */
class HomeController extends AbstractController
{
    use RedisPopulate;

    /**
     * Show homepage.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request Request.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/", name="homepage")
     */
    public function index(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $users = $entityManager->getRepository(User::class)->findAll();
        $communities = $entityManager->getRepository(Community::class)->findAll();
        $sum_uev = $entityManager->getRepository(Ad::class)->sumUEV();

        if (null === $sum_uev[1]) {
            $sum_uev = 0;
        } else {
            $sum_uev = $sum_uev[1];
        }

        $ad = new Ad();
        $ad->setUser($this->getUser());

        $form = $this->createForm(AdType::class, $ad);
        $form->handleRequest($request);

        return $this->render(
            'homepage/index.html.twig',
            [
                'categories' => $this->getRedisCategories(),
                'count_communities' => \count($communities),
                'count_users' => \count($users),
                'count_uev' => $sum_uev,
                'form' => $form->createView(),
            ]
        );
    }
}

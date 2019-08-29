<?php

declare(strict_types=1);

/**
 * This file is a part of Helpee
 *
 * @author  Kevin Allard <contact@allard-kevin.fr>
 * @license 2018
 */

namespace App\Controller\Community;

use App\Entity\Ad\Ad;
use App\Entity\City;
use App\Entity\Community;
use App\Form\CommunityType;
use App\Repository\CommunityRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class CommunityController.
 *
 * @Route("/community", name="community")
 *
 * @IsGranted("ROLE_USER")
 */
class CommunityController extends AbstractController
{
    private $_translator;

    /**
     * CommunityController constructor.
     *
     * @param \Symfony\Contracts\Translation\TranslatorInterface $translator Translator.
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->_translator = $translator;
    }

    /**
     * Create a new community.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request Request.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/new", methods={"GET", "POST"}, name="_new")
     */
    public function new(Request $request): Response
    {
        $community = new Community();
        $community->setCreator($this->getUser());

        $form = $this->createForm(CommunityType::class, $community);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $city = $request->request->get('_city');
            $city = $entityManager
                ->getRepository(City::class)
                ->findOneBy(['id' => $city]);
            $community->setCity($city);
            $community->addMember($this->getUser());

            $entityManager->persist($community);
            $entityManager->flush();

            $this->addFlash(
                'success',
                $this->_translator->trans('community.flash.message.success')
            );

            return $this->redirectToRoute(
                'community_show', ['slug' => $community->getSlug()]
            );
        }

        return $this->render(
            'community/new.html.twig', [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Get list of all communities with paginate.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request   Request.
     * @param int                                       $page      Page.
     * @param \App\Repository\CommunityRepository       $community Community.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/list", defaults={"page" = "1"}, methods={"GET"}, name="_list")
     *
     * @Route("/list/{page<[1-9]\d*>}", methods={"GET"}, name="_list_paginated")
     */
    public function list(
        Request $request,
        int $page,
        CommunityRepository $community
    ): Response {
        return $this->render(
            'community/list.html.twig', [
                'paginator' => $community->getActiveCommunities($page),
            ]
        );
    }

    /**
     * Show community details.
     *
     * @param \App\Entity\Community $community Community.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/{slug}", methods={"GET"}, name="_show")
     */
    public function show(Community $community): Response
    {
        return $this->render(
            'community/show.html.twig', [
                'community' => $community,
                'ads' => $this
                    ->getDoctrine()
                    ->getManager()
                    ->getRepository(Ad::class)
                    ->getActiveAds($community, 5),
            ]
        );
    }

    /**
     * Join a community.
     *
     * @param \App\Entity\Community $community Community.
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/{slug}/join", name="_join")
     */
    public function join(Community $community)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $userInCommunity = $entityManager
            ->getRepository(Community::class)
            ->userInCommunity($community, $this->getUser());

        if (!$userInCommunity) {
            $community->addMember($this->getUser());

            $entityManager->persist($community);
            $entityManager->flush();

            return $this->redirectToRoute(
                'community_show', ['slug' => $community->getSlug()]
            );
        }

        return $this->redirectToRoute(
            'community_show', ['slug' => $community->getSlug()]
        );
    }
}

<?php

declare(strict_types=1);

/**
 * This file is a part of Helpee
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
 * @IsGranted("ROLE_USER")
 */
class CommunityController extends AbstractController
{
    private $translator;

    /**
     * CommunityController constructor.
     *
     * @param \Symfony\Contracts\Translation\TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @Route("/new", methods={"GET", "POST"}, name="_new")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
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
            $city = $entityManager->getRepository(City::class)->findOneBy(['id' => $city]);
            $community->setCity($city);
            $community->addMember($this->getUser());

            $entityManager->persist($community);
            $entityManager->flush();

            $this->addFlash(
                'success',
                $this->translator->trans('community.flash.message.success')
            );

            return $this->redirectToRoute('community_show', ['slug' => $community->getSlug()]);
        }

        return $this->render('community/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/list", defaults={"page" = "1"}, methods={"GET"}, name="_list")
     * @Route("/list/{page<[1-9]\d*>}", methods={"GET"}, name="_list_paginated")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param int                                       $page
     * @param \App\Repository\CommunityRepository       $community
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function list(Request $request, int $page, CommunityRepository $community): Response
    {
        return $this->render('community/list.html.twig', [
            'paginator' => $community->getActiveCommunities($page),
        ]);
    }

    /**
     * @Route("/{slug}", methods={"GET"}, name="_show")
     *
     * @param \App\Entity\Community $community
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(Community $community): Response
    {
        return $this->render('community/show.html.twig', [
            'community' => $community,
            'ads' => $this->getDoctrine()->getManager()->getRepository(Ad::class)->getActiveAds($community, 5),
        ]);
    }

    /**
     * @Route("/{slug}/join", name="_join")
     *
     * @param \App\Entity\Community $community
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function join(Community $community)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $userInCommunity = $entityManager->getRepository(Community::class)->userInCommunity($community, $this->getUser());

        if (!$userInCommunity) {
            $community->addMember($this->getUser());

            $entityManager->persist($community);
            $entityManager->flush();

            return $this->redirectToRoute('community_show', ['slug' => $community->getSlug()]);
        }

        return $this->redirectToRoute('community_show', ['slug' => $community->getSlug()]);
    }
}

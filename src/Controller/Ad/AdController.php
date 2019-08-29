<?php

declare(strict_types=1);

/**
 * This file is a part of Helpee
 *
 * @author  Kevin Allard <contact@allard-kevin.fr>
 * @license 2018
 */

namespace App\Controller\Ad;

use App\Controller\Traits\RedisPopulate;
use App\Entity\Ad\Ad;
use App\Entity\Community;
use App\Form\AdType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class AdController.
 *
 * @Route("/ad", name="ad")
 *
 * @IsGranted("ROLE_USER")
 */
class AdController extends AbstractController
{
    use RedisPopulate;

    private $_translator;

    /**
     * RegisterController constructor.
     *
     * @param \Symfony\Contracts\Translation\TranslatorInterface $translator $translator.
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * Create a new ad.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request Request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/new", methods={"GET", "POST"}, name="_new")
     *
     * @SuppressWarnings(PHPMD.ShortVariable)
     */
    public function new(Request $request): Response
    {
        $ad = new Ad();
        $ad->setUser($this->getUser());

        $form = $this->createForm(AdType::class, $ad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $community = $entityManager->getRepository(Community::class)->findOneBy(['id' => $request->request->get('_community')]);
            $ad->setCommunity($community);

            $entityManager->persist($ad);
            $entityManager->flush();

            $this->addFlash(
                'success',
                $this->translator->trans('ad.flash.message.success')
            );

            return $this->redirectToRoute('homepage');
        }

        return $this->render(
            'ad/new.html.twig', [
                'categories' => $this->getRedisCategories(),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/list", methods={"GET"}, name="_list")
     */
    public function list(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        return $this->render(
            "ad/list.html.twig",
            [
                'ads' => $entityManager->getRepository(Ad::class)->findBy(['user' => $this->getUser()])
            ]
        );
    }

    /**
     * Read an Ad.
     *
     * @param \App\Entity\Ad\Ad $ad Ad.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/{slug}", methods={"GET"}, name="_show")
     *
     * @SuppressWarnings(PHPMD.ShortVariable)
     */
    public function show(Ad $ad): Response
    {
        return $this->render(
            'ad/show.html.twig',
            [
                'ad' => $ad,
            ]
        );
    }
}

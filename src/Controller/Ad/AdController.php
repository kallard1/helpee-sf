<?php

declare(strict_types=1);

/**
 * This file is a part of Helpee.
 *
 * @author  Kevin Allard <contact@allard-kevin.fr>
 *
 * @license 2018-2019 - Helpee
 */

namespace App\Controller\Ad;

use App\Controller\Traits\RedisPopulateTrait;
use App\Entity\Ad\Ad;
use App\Entity\Ad\Category;
use App\Entity\Community;
use App\Form\AdType;
use App\Repository\Ad\AdRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
    use RedisPopulateTrait;

    private $translator;

    /**
     * RegisterController constructor.
     *
     * @param \Symfony\Contracts\Translation\TranslatorInterface $translator $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * Create a new ad.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/new", methods={"GET", "POST"}, name="_new")
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
            $category = $entityManager->getRepository(Category::class)->findOneBy(['slug' => $request->request->get('_category')]);

            $ad->setCategory($category);
            $ad->setCommunity($community);

            $entityManager->persist($ad);
            $entityManager->flush();

            $this->addFlash(
                'success',
                $this->translator->trans('ad.flash.message.success')
            );

            return $this->redirectToRoute('homepage');
        }

        return $this->render('ad/new.html.twig', [
            'categories' => $this->getRedisCategories(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * Get user ad list.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/list", methods={"GET"}, name="_list")
     */
    public function list(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        return $this->render('ad/list.html.twig', [
            'ads' => $entityManager->getRepository(Ad::class)->findBy([
                'user' => $this->getUser(),
                'enabled' => true,
            ]),
        ]);
    }

    /**
     * Read an Ad.
     *
     * @param \App\Entity\Ad\Ad $ad
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/{slug}", methods={"GET"}, name="_show")
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

    /**
     * @Route("/delete/{slug}", name="_delete")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Entity\Ad\Ad                         $ad
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @throws \Exception
     */
    public function delete(Request $request, Ad $ad): RedirectResponse
    {
        if ($ad->getUser() !== $this->getUser()) {
            throw new \Exception("Vous ne pouvez pas désactiver une annonce qui ne vous appartient pas.");
        }

        $em = $this->getDoctrine()->getManager();

        $ad->setEnabled(false);

        $em->persist($ad);
        $em->flush();

        $this->addFlash('success', "Annonce désactivée");

        return $this->redirectToRoute('ad_list');
    }
}

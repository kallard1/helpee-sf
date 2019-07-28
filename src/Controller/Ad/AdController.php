<?php

declare(strict_types=1);

namespace App\Controller\Ad;

use App\Entity\Ad\Ad;
use App\Entity\Community;
use App\Form\AdType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class AdController
 *
 * @package App\Controller\Ad
 * @Route("/ad", name="ad")
 */
class AdController extends AbstractController
{
    private $translator;

    /**
     * RegisterController constructor.
     *
     * @param \Symfony\Contracts\Translation\TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @Route("/new", methods={"GET", "POST"}, name="_new")
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
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

        return $this->render("ad/new.html.twig", [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}", methods={"GET"}, name="_show")
     * @param \App\Entity\Ad\Ad $ad
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(Ad $ad): Response
    {
        return $this->render('ad/show.html.twig', [
            'ad' => $ad
        ]);
    }
}

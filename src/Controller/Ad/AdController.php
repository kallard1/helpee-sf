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

/**
 * Class AdController
 *
 * @package App\Controller\Ad
 * @Route("/ad", name="ad")
 */
class AdController extends AbstractController
{
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

            return $this->redirectToRoute('homepage');
        }

        return $this->render("ad/new.html.twig", [
            'form' => $form->createView(),
        ]);
    }
}

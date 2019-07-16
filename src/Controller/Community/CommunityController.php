<?php

declare(strict_types=1);

namespace App\Controller\Community;

use App\Entity\City;
use App\Entity\Community;
use App\Form\CommunityType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class CommunityController
 *
 * @package App\Controller\Community
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

//            return $this->redirectToRoute('homepage');
        }

        return $this->render('community/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
}

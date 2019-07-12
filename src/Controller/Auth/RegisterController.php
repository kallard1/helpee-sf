<?php

declare(strict_types=1);

namespace App\Controller\Auth;

use App\Entity\City;
use App\Entity\InformationUser;
use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class RegisterController
 *
 * @package App\Controller\Auth
 * @Route("/register")
 */
class RegisterController extends AbstractController
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
     * @Route("/", methods={"GET", "POST"}, name="registration")
     *
     * @param \Symfony\Component\HttpFoundation\Request                             $request
     * @param \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface $passwordEncoder
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response {
        if (null !== $this->getUser()) {
            return $this->redirectToRoute('homepage');
        }

        $user = new User();
        $informationUser = new InformationUser();

        $user->setInformationUser($informationUser);

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $city = $request->request->get('_city');
            $city = $this->getDoctrine()->getManager()->getRepository(City::class)->findOneBy(['id' => $city]);
            $informationUser->setCity($city);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash(
                'success',
                $this->translator->trans('registration.flash.message.success')
            );

            return $this->redirectToRoute('homepage');
        }

        return $this->render('auth/register/registration.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

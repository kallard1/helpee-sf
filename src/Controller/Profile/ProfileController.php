<?php

declare(strict_types=1);

/**
 * This file is a part of Helpee.
 *
 * @author  Kevin Allard <contact@allard-kevin.fr>
 *
 * @license 2018-2019 - Helpee
 */

namespace App\Controller\Profile;

use App\Form\Type\ChangeDescriptionType;
use App\Form\Type\ChangeEmailType;
use App\Form\Type\ChangePasswordType;
use App\Form\Type\ChangePersonalImageType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class ProfileController.
 *
 * @Route("/profile", name="profile")
 *
 * @IsGranted("ROLE_USER")
 */
class ProfileController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"}, name="_main")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(): Response
    {
        return $this->render('profile/index.html.twig');
    }

    /**
     * @Route("/edit-password", methods={"GET", "POST"}, name="_edit_password")
     *
     * @param \Symfony\Component\HttpFoundation\Request                             $request
     * @param \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface $encoder
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function changePassword(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(ChangePasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($encoder->encodePassword($user, $form->get('newPassword')->getData()));

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('logout');
        }

        return $this->render('profile/edit-password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit-email", methods={"GET", "POST"}, name="_edit_email")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function changeEmail(Request $request): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(ChangeEmailType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setEmail($form->get('email')->getData());

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('profile_main');
        }

        return $this->render('profile/edit-email.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit-description", methods={"GET", "POST"}, name="_edit_description")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function changeDescription(Request $request): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(ChangeDescriptionType::class, $user->getInformationUser());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->getInformationUser()->setDescription($form->get('description')->getData());

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('profile_main');
        }

        return $this->render('profile/edit-description.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit-personal-image", methods={"GET", "POST"}, name="_edit_personal_image")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function changePersonalImage(Request $request): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ChangePersonalImageType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $personalImage = $form->get('personal_image')->getData();

            if ($personalImage) {
                $originalFilename = pathinfo($personalImage->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$personalImage->guessExtension();

                try {
                    $personalImage->move(
                        $this->getParameter('personal_images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }

                $user->setPersonalImage($newFilename);
                $this->getDoctrine()->getManager()->flush();
            }

            return $this->redirectToRoute('profile_main');
        }

        return $this->render('profile/edit-personal-image.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

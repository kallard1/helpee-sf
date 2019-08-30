<?php

declare(strict_types=1);

/**
 * This file is a part of Helpee.
 *
 * @author  Kevin Allard <contact@allard-kevin.fr>
 *
 * @license 2018-2019 - Helpee
 */

namespace App\Controller\Auth;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class SecurityController.
 */
class SecurityController extends AbstractController
{
    private $authChecker;

    /**
     * SecurityController constructor.
     *
     * @param \Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authChecker = $authorizationChecker;
    }

    /**
     * @Route("/login", methods={"GET", "POST"}, name="login")
     *
     * @param \Symfony\Component\Security\Http\Authentication\AuthenticationUtils $authenticationUtils
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if (null !== $this->getUser()) {
            return $this->redirectToRoute('homepage');
        }

        return $this->render('auth/security/login.html.twig', [
            'last_username' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError(),
        ]);
    }

    /**
     * @Route("/login_check", methods={"POST"}, name="login_check")
     */
    public function checkAction()
    {
        throw new \RuntimeException('You must configure the check path to be handled by the firewall using form_login in your security firewall configuration.');
    }

    /**
     * @Route("/logout", methods={"GET", "POST"}, name="logout")
     *
     * @throws \Exception
     */
    public function logout()
    {
        throw new \Exception('This should never be reached!');
    }
}

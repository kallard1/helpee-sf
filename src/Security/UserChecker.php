<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\User as AppUser;
use Symfony\Component\Security\Core\Exception\DisabledException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    /**
     * Checks the user account before authentication.
     *
     * @param UserInterface $user
     */
    public function checkPreAuth(UserInterface $user)
    {
        if (!$user instanceof AppUser) {
            return;
        }

        if (true !== $user->getIsEnabled() && false === $user->getIsBanned()) {
            throw new DisabledException('This account is disabled.');
        }

        if (true !== $user->getIsEnabled() && true === $user->getIsBanned()) {
            throw new DisabledException('This account is banned.');
        }
    }

    /**
     * Checks the user account after authentication.
     *
     * @param UserInterface $user
     */
    public function checkPostAuth(UserInterface $user)
    {
        // TODO: Implement checkPostAuth() method.
    }
}

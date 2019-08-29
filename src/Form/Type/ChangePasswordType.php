<?php

declare(strict_types=1);

/**
 * This file is a part of Helpee
 * @author  Kevin Allard <contact@allard-kevin.fr>
 *
 * @license 2018
 */

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class ChangePasswordType
 *
 */
class ChangePasswordType extends AbstractType
{
    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array                                        $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('currentPassword', PasswordType::class, [
                'constraints' => [
                    new UserPassword(),
                ],
            ])
        ->add('newPassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'constraints' => [
                new NotBlank(),
                new Length([
                    'min' => 8,
                    'max' => 128,
                ]),
            ],
        ]);
    }
}

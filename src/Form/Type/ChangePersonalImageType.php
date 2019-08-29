<?php

declare(strict_types=1);

/**
 * This file is a part of Helpee
 *
 * @author  Kevin Allard <contact@allard-kevin.fr>
 *
 * @license 2018
 */

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;

/**
 * Class ChangePersonalImageType
 *
 */
class ChangePersonalImageType extends AbstractType
{
    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array                                        $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('personal_image', FileType::class, [
                'required' => false,
                'constraints' => new File([
                    'mimeTypes' => [
                        'image/jpeg',
                        'image/png',
                    ],
                ]),
            ]);
    }
}

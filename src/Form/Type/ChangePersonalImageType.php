<?php

declare(strict_types=1);

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;

class ChangePersonalImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('personal_image', FileType::class, [
                'required' => false,
                'constraints' => new File([
                    'mimeTypes' => [
                        'image/jpeg',
                        'image/png'
                    ]
                ])
            ])
            ;
    }
}
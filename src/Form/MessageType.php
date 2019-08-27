<?php

declare(strict_types=1);

/**
 * This file is a part of Helpee
 * @author  Kevin Allard <contact@allard-kevin.fr>
 * @license 2018
 */

namespace App\Form;

class MessageType extends \Symfony\Component\Form\AbstractType
{
    public function buildForm(\Symfony\Component\Form\FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('body', \Symfony\Component\Form\Extension\Core\Type\TextareaType::class);
    }

    public function configureOptions(\Symfony\Component\OptionsResolver\OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => \App\Entity\Ad\Message\Message::class,
        ]);
    }
}

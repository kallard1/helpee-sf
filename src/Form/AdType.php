<?php

declare(strict_types=1);

/**
 * This file is a part of Helpee
 *
 * @author  Kevin Allard <contact@allard-kevin.fr>
 *
 * @license 2018
 */

namespace App\Form;

use App\Entity\Ad\Ad;
use App\Entity\Ad\Category;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AdType
 *
 */
class AdType extends AbstractType
{
    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder builder.
     * @param array                                        $options options.
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class)
            ->add('description', TextareaType::class)
            ->add('uev', NumberType::class);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver resolver.
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                "data_class" => Ad::class,
                "allow_extra_fields" => true
            ]
        );
    }
}

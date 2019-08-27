<?php

declare(strict_types=1);

/**
 * This file is a part of Helpee
 * @author  Kevin Allard <contact@allard-kevin.fr>
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

class AdType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('category', EntityType::class, [
                'multiple' => false,
                'class' => Category::class,
                'choice_label' => 'label',
                'choice_value' => 'id',
                'query_builder' => function (EntityRepository $entityRepository) {
                    $qb = $entityRepository->createQueryBuilder('c')
                        ->addOrderBy('c.root', 'ASC')
                        ->addOrderBy('c.lft', 'ASC');

                    return $qb;
                },
                'required' => true,
                'expanded' => false,
            ])
            ->add('title', TextType::class)
            ->add('description', TextareaType::class)
            ->add('uev', NumberType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}

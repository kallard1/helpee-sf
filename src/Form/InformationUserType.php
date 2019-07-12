<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\City;
use App\Entity\InformationUser;
use App\Form\DataTransformer\CityEntityToPropertyTransformer;
use Doctrine\DBAL\Types\ArrayType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

class InformationUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('address', TextType::class)
            ->add('address1', TextType::class)
            ->add('phone', TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => InformationUser::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Adresse;
use Symfony\Component\Form\AbstractType;
use App\Entity\AdresseType as typeAdresse;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AdresseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('adresseType', EntityType::class, [
                'class' => typeAdresse::class,
                'placeholder' => 'Choisir un type d\'adresse',
                'choice_label' => 'libelle',

            ])
            ->add('voie', TextType::class, [
                'attr' => [
                    'placeholder' => 'Rue',
                ],
            ])
            ->add('complement', TextType::class, [
                'attr' => [
                    'placeholder' => 'Complément d\'adresse',
                    'requierd' => false,
                ]
            ])
            ->add('boitePostale', TextType::class, [
                'attr' => [
                    'placeholder' => 'Boite postale',
                    'required' => false,
                ]
            ])
            ->add('codePostal', TextType::class, [
                'attr' => [
                    'placeholder' => 'Code postal'
                ]
            ])
            ->add('ville', TextType::class, [
                'attr' => [
                    'placeholder' => 'Ville',
                ]
            ])
            ->add('region', TextType::class, [
                'attr' => [
                    'placeholder' => 'Région',
                ]
            ])
            ->add('departement', TextType::class, [
                'attr' => [
                    'placeholder' => 'Département',
                ]
            ])
            ->add('pays', TextType::class, [
                'attr' => [
                    'placeholder' => 'Pays'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Adresse::class,
        ]);
    }

    public function getBlockPrefix()
    {
        return 'AddressType';
    }
}

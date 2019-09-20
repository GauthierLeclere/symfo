<?php

namespace App\Form;

use App\Entity\Client;
use App\Form\AdresseType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'attr' =>[
                    'placeholder' => 'Nom du client',
                ]
            ])
            ->add('prenom', TextType::class, [
                'attr' => [
                    'placeholder' => 'Prénom du client',
                ]
            ])
            ->add('adresses', CollectionType::class, [
                'entry_type' => AdresseType::class,
                'entry_options' => [
                    'attr' => ['class' => 'adresse-box'],
                    'label' => false,
                ],
                'block_name' => 'adresse_lists',
                'prototype'    => true,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'attr' => [
                    'class' => 'adresses_collection',
                ]
            ])
            ->add('commentaire', TextareaType::class, [
                'required' => false,
            ])
            ->add('pictureFiles', FileType::class, [
                'required' => false,
                'multiple' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }

}

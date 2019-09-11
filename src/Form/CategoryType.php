<?php

namespace App\Form;

use App\Entity\Category;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('parent', EntityType::class, array(
                'label' => 'Catégorie parent',
                    'required'  =>  false,
                    'class' => Category::class,
                    'choice_label'  => function(Category $category){
                        
                        $prefix = str_repeat('-', $category->getLvl());
                        
                        return $prefix . ' ' . $category->getTitle();
                    },
                    'multiple'  => false,
                    'expanded'  => false,
                    'query_builder' => function(EntityRepository $er){
                        
                        return $er
                                ->createQueryBuilder('node')
                                ->orderBy('node.root, node.lft', 'ASC');
                    }
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}

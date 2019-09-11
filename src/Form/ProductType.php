<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Category;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('reference', TextType::class, [
                'required' => true,
            ])
            ->add('nom')
            ->add('designation')
            ->add('categories', EntityType::class, array(
                'label' => 'Catégorie',
                    'required'  =>  false,
                    'class' => Category::class,
                    'choice_label'  => function(Category $category){
                        
                        $prefix = str_repeat('-', $category->getLvl());
                        
                        return $prefix . ' ' . $category->getTitle() . '('.$category->getLvl().')';
                    },
                    'multiple'  => true,
                    'expanded'  => false,
                    'query_builder' => function(EntityRepository $er){
                        
                        return $er
                                ->createQueryBuilder('node')
                                ->orderBy('node.root, node.lft', 'ASC');
                    }
            ))
            ->add('prixHt')
            ->add('stock')
            ->add('imageFile', FileType::class, [
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}

<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/category", name="category_")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/", name="category_index")
     */
    public function index()
    {
        $repo = $this->getDoctrine()->getRepository(Category::class);
        $options = array(
            'decorate' => true,
            'rootOpen' => '<ul>',
            'rootClose' => '</ul>',
            'childOpen' => '<li>',
            'childClose' => '</li>',
            'nodeDecorator' => function($node) {
                return '<a href="/page/'.$node['title'].'">'.$node['title'].'</a>';
            }
        );
        $htmlTree = $repo->childrenHierarchy(
            null, /* starting from root nodes */
            false, /* true: load all children, false: only direct */
            $options
        );

        $controller = $this;
        $tree = $repo->childrenHierarchy(null,false,array('decorate' => true,
            'rootOpen' => function($tree) {
                if(count($tree) && ($tree[0]['lvl'] == 0)){
                        return '<div class="catalog-list">';
                }
            },
            'rootClose' => function($child) {
                if(count($child) && ($child[0]['lvl'] == 0)){
                                return '</div>';
                }
             },
            'childOpen' => '',
            'childClose' => '',
            'nodeDecorator' => function($node) use (&$controller) {
                if($node['lvl'] == 1) {
                    return '<h1>'.$node['title'].'</h1>';
                }else {
                   return '<a href="/page/'.$node['title'].'">'.$node['title'].'</a>';
                }
            }
        ));

        return $this->render('Admin/category/index.html.twig', [
            'htmlTree' => $htmlTree,
            'tree' => $tree,
        ]);
    }

    /**
     * @Route("/new", name="category_new")
     */
    public function new(Request $request)
    {
        $category = new Category();
        
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
            
            // Message flash
            $this->addFlash('success', 'La catégorie est ajoutée avec succès.');
            
            // Re-initialise le formulaire pour pouvoir ajouter une nouvelle catégorie
            // Vous pouvez aussi rediriger vers une page
            
            $form = $this->createForm(CategoryType::class, new Category());
        }
        
        return $this->render('Admin/category/new.html.twig', array(
            'form'  => $form->createView()
        ));
    }

    /**
     *  @Route("/edit/{id}", name="category_edit")
     */
    public function edit(Category $category)
    {
        dump($category); die;
    }

    /**
     * @Route("/generate", name="generate_category")
     */
    public function generate()
    {

        // INIT
        $em = $this->getDoctrine()->getManager();

        $food = new Category();
        $food->setTitle('Food');

        $fruits = new Category();
        $fruits->setTitle('Fruits');
        $fruits->setParent($food);

        $vegetables = new Category();
        $vegetables->setTitle('Vegetables');
        $vegetables->setParent($food);

        $carrots = new Category();
        $carrots->setTitle('Carrots');
        $carrots->setParent($vegetables);

        $em->persist($food);
        $em->persist($fruits);
        $em->persist($vegetables);
        $em->persist($carrots);
        $em->flush();
    }
}

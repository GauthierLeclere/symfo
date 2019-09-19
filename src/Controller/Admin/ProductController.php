<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/product")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/", name="product_index", methods={"GET"})
     */
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('Admin/product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="product_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

            $this->addFlash('success', 'Le produit a été ajouté avec succes');

            return $this->redirectToRoute('product_index');
        }

        return $this->render('Admin/product/new.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/new-modal", name="product_new_modal", methods={"GET","POST"}, options={"expose"=true})
     */
    public function newModal(Request $request): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

            $this->addFlash('success', 'Le produit a été ajouté avec succes');

            return $this->json([
                'valid' => true,
            ]);
        }

        return $this->render('Admin/product/new_modal.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/show/{id}", name="product_show", methods={"GET"}, options={"expose"=true})
     */
    public function show(Product $product): Response
    {
        return $this->render('Admin/product/show.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="product_edit", methods={"GET","POST"}, options={"expose"=true})
     */
    public function edit(Request $request, Product $product): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('product_index');
        }

        return $this->render('Admin/product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="product_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Product $product): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('Admin/product_index');
    }

    /**
     * @Route("delete/{id}", name="product_delete_ajax", methods={"POST"}, options={"expose"=true})
     */
    public function deleteAjax(Request $request, Product $product): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($product);
        $entityManager->flush();

        return $this->Json([
            'valid' => true,
        ]);
    }



    /**
     * @Route("/liste-charger", name="product_liste_datatable", options={"expose"=true})
     */
    public function indexAction(Request $request, ProductRepository $productRepository, UploaderHelper $helper, SerializerInterface $serializer) {

        $ordering = [
            '0' => 'p.id',
            '2' => 'p.nom'
        ];

        $order = $request->get('order')[0]['column'];

        $orderDir = $request->get('order')[0]['dir'];

        $orderColumn = $ordering[$order];
       
        $length = $request->get('length');
        $length = $length && ($length!=-1)?$length:0;
 
        $start = $request->get('start');
        $start = $length?($start && ($start!=-1)?$start:0)/$length:0;

        $search = $request->get('search');

        $filters = [
            'query' => $search
        ];

        $products = $productRepository->search(
            $filters, $start, $length, true , $orderColumn, $orderDir
        );

        $output = array(
            'data' => array(),
            'recordsFiltered' => count($this->getDoctrine()->getRepository(Product::class)->search($filters, 0, false)),
            'recordsTotal' => count($this->getDoctrine()->getRepository(Product::class)->search(array(), 0, false))
        );

        foreach ($products as $product) {

            // Serialize your object in Json
            $categories = $serializer->serialize($product->getCategories(), 'json', [
                'circular_reference_handler' => function ($object) {
                    return $object->getTitle();
                }
            ]);

            $output['data'][] = [
                'id' => $product->getId(),
                'reference' => $product->getReference(),
                'visuel' => $helper->asset($product, 'imageFile'),
                'categories' => $categories,
                'nom' => $product->getNom(),
                'designation' => $product->getDesignation(),
                'prixHt' => $product->getPrixHt(),
                'stock' => $product->getStock(),
                'action' => '',
            ];
        }


        return $this->json($output);
    }


}

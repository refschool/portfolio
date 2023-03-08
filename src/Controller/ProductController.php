<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;

class ProductController extends AbstractController
{
    /**
     * @Route("/product", name="app_product")
     */
    public function index(): Response
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

    /**
     * @Route("/produits", name="produits")
     */
    public function produits(ProductRepository $productRepository)
    {
        $products = $productRepository->findBy([], [], 3);
        return $this->render('produits.html.twig', [
            'products' => $products
        ]);
    }
}

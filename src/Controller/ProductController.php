<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ProductController extends AbstractController
{
    /**
     * @Route("/produits", name="produits")
     */
    public function produits(ProductRepository $productRepository)
    {
        $products = $productRepository->findBy([], [], 3);
        return $this->render('product/produits.html.twig', [
            'products' => $products
        ]);
    }
    /**
     * @Route("/{slug}",priority=-1, name="product_category")
     */
    public function category($slug, CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->findOneBy([
            'slug' => $slug
        ]);

        if (!$category) {
            throw $this->createNotFoundException("La catégorie demandé n'existe pas.");
        }
        return $this->render('product/category.html.twig', [
            'slug' => $slug,
            'category' => $category
        ]);
    }


    /**
     * @Route("/{category_slug}/{slug}", priority=-1,name="product_show" )
     */
    public function show($slug, ProductRepository $productRepository)
    {
        $product = $productRepository->findOneBy([
            'slug' => $slug,

        ]);
        if (!$product) {
            throw $this->createNotFoundException("Le produit demandé n'existe pas.");
        }
        return $this->render("product/show.html.twig", [
            'product' => $product,
        ]);
    }
}

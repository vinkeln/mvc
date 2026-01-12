<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\ProductRepository;
use App\Service\ProductService;

final class ProductController extends AbstractController
{
    public function __construct(private ProductService $productService)
    {
    }

    #[Route('/product', name: 'app_product')]
    public function index(): Response
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

    #[Route('/product/create', name: 'product_create')]
    public function create(): Response
    {
        $product = $this->productService->createRandomProduct();

        return new Response('Saved new product with id '.$product->getId());
    }

    #[Route('/product/show', name: 'product_show_all')]
    public function showAll(): Response
    {
        return $this->jsonPretty($this->productService->getAllProducts());
    }

    #[Route('/product/show/{id}', name: 'product_by_id')]
    public function showById(int $id): Response
    {
        $product = $this->productService->getProductOrFail($id);
        return $this->jsonPretty($product);
    }

    #[Route('/product/delete/{id}', name: 'product_delete_by_id')]
    public function delete(int $id): Response
    {
        $this->productService->deleteProduct($id);
        return $this->redirectToRoute('product_show_all');
    }

    #[Route('/product/update/{id}/{value}', name: 'product_update')]
    public function update(int $id, int $value): Response
    {
        $this->productService->updateProductValue($id, $value);
        return $this->redirectToRoute('product_show_all');
    }

    #[Route('/product/view', name: 'product_view_all')]
    public function viewAll(ProductRepository $repo): Response
    {
        return $this->render('product/view.html.twig', [
            'products' => $repo->findAll()
        ]);
    }

    #[Route('/product/view/{value}', name: 'product_view_minimum_value')]
    public function viewMinValue(ProductRepository $repo, int $value): Response
    {
        return $this->render('product/view.html.twig', [
            'products' => $repo->findByMinimumValue($value)
        ]);
    }

    #[Route('/product/show/min/{value}', name: 'product_by_min_value')]
    public function showMinValue(ProductRepository $repo, int $value): Response
    {
        return $this->jsonPretty($repo->findByMinimumValue2($value));
    }

    /**
     * Hjälpmetod för JSON med pretty print
     */
    private function jsonPretty(array|object $data): Response
    {
        $response = $this->json($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
}

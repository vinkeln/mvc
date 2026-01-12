<?php

namespace App\Service;

use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductService
{
    public function __construct(private ManagerRegistry $doctrine)
    {
    }

    /**
     * Skapar ett nytt produkt med slumpmässigt namn och värde
     */
    public function createRandomProduct(): Product
    {
        $product = new Product();
        $product->setName('Keyboard_num_' . rand(1, 9));
        $product->setValue(rand(100, 999));

        $em = $this->doctrine->getManager();
        $em->persist($product);
        $em->flush();

        return $product;
    }

    /**
     * Hämta alla produkter
     */
    public function getAllProducts(): array
    {
        return $this->doctrine->getRepository(Product::class)->findAll();
    }

    /**
     * Hämta produkt eller kasta 404
     */
    public function getProductOrFail(int $id): Product
    {
        $product = $this->doctrine->getRepository(Product::class)->find($id);

        if (!$product) {
            throw new NotFoundHttpException('No product found for id '.$id);
        }

        return $product;
    }

    /**
     * Uppdatera värdet på produkt
     */
    public function updateProductValue(int $id, int $value): void
    {
        $product = $this->getProductOrFail($id);
        $product->setValue($value);
        $this->doctrine->getManager()->flush();
    }

    /**
     * Ta bort produkt
     */
    public function deleteProduct(int $id): void
    {
        $product = $this->getProductOrFail($id);
        $em = $this->doctrine->getManager();
        $em->remove($product);
        $em->flush();
    }
}

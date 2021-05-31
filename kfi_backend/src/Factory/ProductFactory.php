<?php
namespace App\Factory;

use App\Entity\Product;

class ProductFactory
{
    public function create(array $param): Product
    {
        $book = new Product();
        $book->setName($param['name'])
            ->setEan($param['ean'])
            ->setPrice($param['price'])
            ->setStock($param['stock'])
            ->setPublisher($param['publisher']);

        foreach ($param['authors'] as $author) {
            $book->addAuthor($author);
        }
        return $book;
    }

}
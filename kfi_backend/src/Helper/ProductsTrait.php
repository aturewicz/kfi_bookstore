<?php
namespace App\Helper;

use App\Entity\Author;
use App\Entity\Product;
use App\Entity\Publisher;
use Doctrine\Common\Collections\Collection;

trait ProductsTrait
{

    private function addPropertyChange(array $arr)
    {
        $this->propertyChanges[] = $arr;
    }

    private function getPropertyChanges(): array
    {
        return $this->propertyChanges;
    }

    private function authors(string $authorsString): array
    {
        $fullNames = explode(',', $authorsString);

        $authors = [];
        foreach ($fullNames as $fullName) {
            if (!$this->isAuthor($fullName)) {
                $author = $this->authorFactory->create($fullName);
                $this->em->persist($author);
                $this->em->flush();
            } else {
                $author = $this->em->getRepository(Author::class)
                    ->findOneBy(['fullName' => $fullName]);
            }
            $authors[] = $author;
        }
        return $authors;
    }

    private function publisher(string $publisherName): Publisher
    {
        if (!$this->isPublisher($publisherName)) {
            $publisher = $this->publisherFactory->create($publisherName);
            $this->em->persist($publisher);
            $this->em->flush();
        } else {
            $publisher = $this->em->getRepository(Publisher::class)
                ->findOneBy(['name' => $publisherName]);
        }
        return $publisher;
    }

    private function product(array $param)
    {
        // Add|update product to DB
        if ($this->isBook($param['ean'])) {
            // Update product

            /** @var $product Product */
            $product = $this->em->getRepository(Product::class)
                ->findOneBy(['ean' => $param['ean']]);

            foreach ($param as $prop => $value) {

                // https://stackoverflow.com/questions/11612728/php-how-to-use-dynamic-variable-name-with-a-class-setter
                if ($prop === 'publisher' && $product->{'get' . ucwords($prop)}()->getName() != $value->getName()) {
                    $this->addPropertyChange(
                        [
                            'property' => $prop,
                            'ean' => $param['ean'],
                            'old' => $product->{'get' . ucwords($prop)}()->getName(),
                            'new' => $value->getName()
                        ]);
                    $product->{'set' . ucwords($prop)}($value);
                } elseif ($prop === 'authors') {
                    $compareAuthorsBook = $this->compareAuthorsBook($product->getAuthors(), $param['authors']);
                    // Remove old(not use) author(s)
                    if (count($compareAuthorsBook) > 0) {

                        $this->addPropertyChange(
                            [
                                'property' => $prop,
                                'ean' => $param['ean'],
                                'old' => $this->authorsNameToString($product->getAuthors()),
                                'new' => $this->authorsNameToString($param['authors'])
                            ]);

                        foreach ($compareAuthorsBook as $author) {
                            /** @var Author $authorDB */
                            $authorDB = $this->em->getRepository(Author::class)
                                ->findOneBy(['fullName' => $author]);

                            if ($authorDB) $product->removeAuthor($authorDB);
                        }
                    }

                    // if author(s) not exist add author(s) to product (table DB: product_author)
                    foreach ($param['authors'] as $author) {
                        if (!$product->getAuthors()->contains($author)) $product->addAuthor($author);
                    }
                } elseif ($product->{'get' . ucwords($prop)}() != $value) {
                    $this->addPropertyChange(
                        [
                            'property' => $prop,
                            'ean' => $param['ean'],
                            'old' => $product->{'get' . ucwords($prop)}(),
                            'new' => $value
                        ]);
                    $product->{'set' . ucwords($prop)}($value);
                }
            }
            $this->em->persist($product);
        } else {
            // Add new product
            $this->em->persist($this->productFactory->create($param));
        }
        $this->em->flush();
    }

    private function isAuthor(string $fullName): bool
    {
        $author = $this->em->getRepository(Author::class)
            ->findOneBy(['fullName' => $fullName]);

        return $author !== null;
    }

    private function isPublisher(string $name): bool
    {
        $publisher = $this->em->getRepository(Publisher::class)
            ->findOneBy(['name' => $name]);

        return $publisher !== null;
    }

    private function isBook(string $ean): bool
    {
        $product = $this->em->getRepository(Product::class)
            ->findOneBy(['ean' => $ean]);

        return $product !== null;
    }

    private function authorsNameToString($arrAuthors): string
    {
        $authors = [];
        foreach ($arrAuthors as $author) {
            $authors[] = $author->getFullName();
        }

        return implode(',', $authors);
    }

    /**
     * @param Collection|Authors[] $productAuthors
     * @param Author[] $authors
     * @return array
     */
    private function compareAuthorsBook($productAuthors, array $authors): array
    {

        // authors in DB
        $authorsDB = [];
        foreach ($productAuthors as $author) {
            $authorsDB[] = $author->getFullName();
        }

        // authors in CSV file
        $authorsCSV = [];
        foreach ($authors as $author) {
            $authorsCSV[] = $author->getFullName();
        }

        return array_diff($authorsDB, $authorsCSV);
    }

}
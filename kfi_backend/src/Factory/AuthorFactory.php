<?php
namespace App\Factory;

use App\Entity\Author;

class AuthorFactory
{
    public function create(string $author): Author
    {
        return (new Author())->setFullName($author);
    }
}
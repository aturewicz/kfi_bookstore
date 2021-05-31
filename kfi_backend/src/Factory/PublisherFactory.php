<?php
namespace App\Factory;

use App\Entity\Publisher;

class PublisherFactory
{
    public function create(string $publisher): Publisher
    {
        return (new Publisher())->setName($publisher);
    }
}
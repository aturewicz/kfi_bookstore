<?php

namespace App\Controller;

use App\Entity\Publisher;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ApiPublisherController
 * @package App\Controller
 * @Route("/api")
 */
class ApiPublisherController extends ApiBaseController
{

    /**
     * @Route("/publisher/search/{search}", name="api_search_publishers", methods={"GET"})
     * @param string $search
     * @return JsonResponse
     */
    public function search(string $search):JsonResponse
    {
        $repository = $this->em->getRepository(Publisher::class);
        $publishers = $repository->findPublishers($search);

        return $this->createApiResponse($publishers);
    }
}

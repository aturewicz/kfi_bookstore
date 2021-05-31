<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ApiAuthorController
 * @package App\Controller
 * @Route("/api")
 */
class ApiAuthorController extends ApiBaseController
{

    /**
     * @Route("/author", name="api_author_list", methods={"GET"})
     * @return JsonResponse
     */
    public function list(): JsonResponse
    {
        $repository = $this->em->getRepository(Author::class);
        $authors = $repository->findAllAuthors();

        return $this->createApiResponse(['items' => $authors]);
    }

    /**
     * @Route("/author/search/{search}", name="api_search_authors", methods={"GET"})
     * @param string $search
     * @return JsonResponse
     */
    public function search(string $search):JsonResponse
    {
        $repository = $this->em->getRepository(Author::class);
        $authors = $repository->findAuthors($search);

        return $this->createApiResponse($authors);
    }

    /**
     * @Route("/author/{id}", name="api_author_item", methods={"GET"})
     * @param int $id
     * @return JsonResponse
     */
    public function author(int $id): JsonResponse
    {
        $repository = $this->em->getRepository(Author::class);
        $author = $repository->findOneBy(['id' => $id]);
        $groups = ['author_edit'];

        return $this->createApiResponse($author, $groups);
    }

    /**
     * @Route("/author/{id}", name="api_author_update", methods={"PATCH"})
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $data = $request->toArray();
        $repository = $this->em->getRepository(Author::class);

        /** @var Author $author */
        $author = $repository->findOneBy(['id' => $id]);

        $form = $this->createForm(AuthorType::class, $author);
        $form->submit($data, false);

        if (false === $form->isValid()) {
            return $this->createValidationErrorResponse($form);
        }
        $this->em->flush();

        return $this->createApiResponse([], [], JsonResponse::HTTP_NO_CONTENT);
    }
}

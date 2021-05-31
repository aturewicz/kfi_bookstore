<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Publisher;
use App\Form\ProductType;
use App\Form\PublisherType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ApiProductController
 * @package App\Controller
 * @Route("/api")
 */
class ApiProductController extends ApiBaseController
{
    /**
     * @Route("/product", name="api_product_list", methods={"GET"})
     * @return JsonResponse
     */
    public function list(): JsonResponse
    {
        $repository = $this->em->getRepository(Product::class);
        $products = $repository->findAll();
        $groups = ['product_list'];

        return $this->createApiResponse(['items' => $products], $groups);
    }

    /**
     * @Route("/product/search", name="api_search_products", methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        $author = $request->query->get('author');
        $publisher = $request->query->get('publisher');

        $params = [];
        if ($author !== '') $params['author'] = $author;
        if ($publisher !== '') $params['publisher'] = $publisher;

        $repository = $this->em->getRepository(Product::class);
        $products = $repository->findProducts($params);

        return $this->createApiResponse(['items' => $products]);
    }

    /**
     * @Route("/product/{ean}", name="api_product_item", methods={"GET"})
     * @param string $ean
     * @return JsonResponse
     */
    public function product(string $ean): JsonResponse
    {
        $repository = $this->em->getRepository(Product::class);
        $product = $repository->findOneBy(['ean' => $ean]);
        $groups = ['product_edit'];

        return $this->createApiResponse($product, $groups);
    }

    /**
     * @Route("/product/{ean}", name="api_product_update", methods={"PATCH"})
     * @param Request $request
     * @param string $ean
     * @return JsonResponse
     */
    public function update(Request $request, string $ean): JsonResponse
    {
        $data = $request->toArray();
        $repository = $this->em->getRepository(Product::class);

        /** @var Product $product */
        $product = $repository->findOneBy(['ean' => $ean]);

        $repository = $this->em->getRepository(Publisher::class);
        $publisher = $repository->findOneBy(['name' => $data['publisher']]);

        // if not found publisher then create new publisher
        if (!$publisher) {
            $publisher = new Publisher();

            $request2 = new Request();
            $request2->request->set('name', $data['publisher']);

            $form = $this->createForm(PublisherType::class, $publisher);
            $form->submit($request2->request->all());

            if (false === $form->isValid()) {
                return $this->createValidationErrorResponse($form);
            }
            $this->em->persist($publisher);
            $this->em->flush();
        }
        $data['publisher'] = $publisher->getId();

        $form = $this->createForm(ProductType::class, $product);
        $form->submit($data, false);

        if (false === $form->isValid()) {
            return $this->createValidationErrorResponse($form);
        }
        $this->em->flush();

        return $this->createApiResponse([], [], JsonResponse::HTTP_NO_CONTENT);
    }
}

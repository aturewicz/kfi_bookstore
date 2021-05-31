<?php

declare(strict_types=1);

namespace App\Controller;

use App\Api\ApiProblem;
use App\Api\ApiProblemException;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\Context;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Serializer\FormErrorSerializer;

class ApiBaseController extends AbstractController
{
    private SerializerInterface $jms_serializer;
    protected EntityManagerInterface $em;

    protected FormErrorSerializer $formErrorSerializer;

    public function __construct(SerializerInterface $jms_serializer, EntityManagerInterface $em, FormErrorSerializer $formErrorSerializer)
    {
        $this->jms_serializer = $jms_serializer;
        $this->em = $em;
        $this->formErrorSerializer = $formErrorSerializer;
    }

    protected function createApiResponse($data, array $groups = [], $statusCode = JsonResponse::HTTP_OK): JsonResponse
    {
        $json = $this->serialize($data, $groups);
        return new JsonResponse($json, $statusCode, [], true);
    }

    protected function serialize($data, array $userGroups = [], string $format = 'json'): string
    {
        $defaultGroups = ['Default'];
        $groups = array_merge($defaultGroups, $userGroups);

        $context = new SerializationContext();
        $context->setSerializeNull(true);
        $context->setGroups($groups);

        return $this->jms_serializer->serialize($data, $format, $context);
    }

    /**
     * @param Request $request
     * @param FormInterface $form
     * @throws \HttpException
     */
    protected function processForm(Request $request, FormInterface $form)
    {
        $data = $request->request->all();

        if (null === $data) {
            $apiProblem = new ApiProblem(
                Response::HTTP_BAD_REQUEST,
                ApiProblem::TYPE_INVALID_REQUEST_BODY_FORMAT
            );

            //throw new \HttpException(Response::HTTP_BAD_REQUEST, 'Invalid JSON');
            throw new ApiProblemException($apiProblem);
        }

        $clearMissing = $request->getMethod() != 'PATCH';
        $form->submit($data, $clearMissing);
    }

    /**
     * @param FormInterface $form
     */
    protected function createValidationErrorResponse(FormInterface $form)
    {
        $errors = $this->formErrorSerializer->convertFormToArray($form);

        $apiProblem = new ApiProblem(
            JsonResponse::HTTP_BAD_REQUEST,
            ApiProblem::TYPE_VALIDATION_ERROR
        );
        $apiProblem->set('errors', $errors);

        throw new ApiProblemException($apiProblem);
    }
}
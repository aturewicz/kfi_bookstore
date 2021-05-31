<?php


namespace App\Api;

use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class ApiProblemException
 * @link https://symfonycasts.com/screencast/symfony-rest2/api-problem-exception
 * @package App\Api
 */
class ApiProblemException extends HttpException
{
    /**
     * @var ApiProblem
     */
    private $apiProblem;

    public function __construct(ApiProblem $apiProblem, \Throwable $previous = null, array $headers = [], ?int $code = 0)
    {
        $this->apiProblem = $apiProblem;
        $statusCode = $apiProblem->getStatusCode();
        $message = $apiProblem->getTitle();

        parent::__construct($statusCode, $message, $previous, $headers, $code);
    }

    /**
     * @return ApiProblem
     */
    public function getApiProblem(): ApiProblem
    {
        return $this->apiProblem;
    }
}
<?php

namespace App\Api;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class ApiProblem
 * A wrapper for holding data to be used for a application/problem+json response
 * @package App\Api
 */
class ApiProblem
{

    const TYPE_VALIDATION_ERROR = 'validation_error';
    const TYPE_INVALID_REQUEST_BODY_FORMAT = 'invalid_body_format';

    const ERROR_AUTHOR_NOT_FOUND = 'author_not_found';
    const ERROR_PRODUCT_NOT_FOUND = 'product_not_found';


    /**
     * @var string[]
     */
    private static $titles = [
        self::TYPE_VALIDATION_ERROR => 'There was a validation error.',
        self::TYPE_INVALID_REQUEST_BODY_FORMAT => 'Invalid JSON format sent.',
        self::ERROR_AUTHOR_NOT_FOUND => 'Author not found.',
        self::ERROR_PRODUCT_NOT_FOUND => 'Product not found.'
    ];

    /**
     * @var int
     */
    private $statusCode;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $title;

    /**
     * @var array
     */
    private $extraData = [];

    public function __construct(int $statusCode, ?string $type = null)
    {
        $this->statusCode = $statusCode;
        if ($type === null) {
            // no type? The default is about:blank and the title should
            // be the standard status code message
            $type = 'about:blank';
            $title = Response::$statusTexts[$statusCode] ?? 'Unknown status code.';
        } else {
            if (!isset(self::$titles[$type])) {
                throw new \InvalidArgumentException('No title for type '.$type);
            }
            $title = self::$titles[$type];
        }
        $this->type = $type;
        $this->title = $title;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return array_merge(
            $this->extraData,
            [
                'status' => $this->statusCode,
                'type' => $this->type,
                'title' => $this->title
            ]
        );
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return mixed
     */
    public function set(string $name, $value)
    {
        return $this->extraData[$name] = $value;
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @return mixed|string
     */
    public function getTitle()
    {
        return $this->title;
    }
}
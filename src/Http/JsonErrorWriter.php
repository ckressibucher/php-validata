<?php

namespace Ckr\Validata\Http;

use Ckr\Validata\Err\Err;
use Ckr\Validata\Err\LocationInterface;
use Ckr\Validata\Result;
use Psr\Http\Message\ResponseInterface;

class JsonErrorWriter implements ErrorWriterInterface
{

    /**
     * @var string
     */
    protected $errorField;

    /**
     * @var string
     */
    protected $errorInfoField;

    /**
     * JsonErrorWriter constructor.
     * @param string $errorField
     * @param string $errorInfoField
     */
    public function __construct($errorField = 'message', $errorInfoField = 'errors')
    {
        $this->errorField = $errorField;
        $this->errorInfoField = $errorInfoField;
    }

    /**
     * {@inheritdoc}
     */
    public function makeErrResponse(
        $errMsg,
        $httpCode,
        Result $validationResult,
        ResponseInterface $res
    ) {
        $data = [$this->errorField => $errMsg];
        $data[$this->errorInfoField] = array_map(function (Err $err) {
            $stack = array_map(function (LocationInterface $loc) {
                return [
                    'type' => $loc->getType(),
                    'location' => $loc->getLocation()
                ];
            }, $err->getLocation()->getStack());
            return [
                'error_message' => $err->getMsg(),
                'stack' => $stack,
            ];
        }, $validationResult->getErrors());
        $res->getBody()->write(json_encode($data));
        return $res->withStatus($httpCode)->withHeader('Content-Type', 'application/json');
    }
}

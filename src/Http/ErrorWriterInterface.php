<?php


namespace Ckr\Validata\Http;

use Ckr\Validata\Result;
use Psr\Http\Message\ResponseInterface;

interface ErrorWriterInterface
{

    /**
     * @param string $errMsg
     * @param int $httpCode
     * @param Result $validationResult
     * @param ResponseInterface $res
     * @return ResponseInterface
     */
    public function makeErrResponse(
        $errMsg,
        $httpCode,
        Result $validationResult,
        ResponseInterface $res
    );
}

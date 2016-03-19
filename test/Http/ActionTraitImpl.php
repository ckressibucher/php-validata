<?php


namespace Ckr\Validata\Test\Http;

use Ckr\Validata\Http\ActionTrait;
use Ckr\Validata\Http\ErrorWriterInterface;
use Ckr\Validata\Schema\SchemaInterface;
use Ckr\Validata\Validator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Dummy implementation of a controller using the ActionTrait.
 *
 * Used to test the ActionTrait. You can create a PHPUnit mock
 * to change behaviour of `handleRequestAction` and `getInputData` methods.
 */
class ActionTraitImpl
{

    use ActionTrait;

    /**
     * ActionTraitImpl constructor.
     * @param SchemaInterface $schema
     * @param ErrorWriterInterface $errWriter
     * @param Validator|null $validator
     */
    public function __construct($schema, $errWriter, $validator = null)
    {
        $this->init($schema, $errWriter, $validator);
    }

    /**
     * Wraps protected trait method `handleRequest`
     *
     * @param ServerRequestInterface $req
     * @param ResponseInterface $res
     * @return ResponseInterface
     */
    public function run(ServerRequestInterface $req, ResponseInterface $res)
    {
        return $this->handleRequest($req, $res);
    }

    protected function handleRequestAction(
        array $validData,
        ServerRequestInterface $req,
        ResponseInterface $res
    ) {
    }

    protected function getInputData(ServerRequestInterface $req)
    {
        return [];
    }
}

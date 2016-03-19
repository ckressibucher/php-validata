<?php


namespace Ckr\Validata\Http;

use Ckr\Validata\Schema\SchemaInterface;
use Ckr\Validata\Validator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Basic controller action implementation, using validation schemas.
 *
 * To implement an action, extend this class and define the method
 * `handleRequestAction`,
 *
 * If you're using a framework, you probably have to create your
 * own base class to bind the validation functionality to the
 * controller interface of the framework. Maybe the `ActionTrait`
 * may be useful in this case.
 *
 */
abstract class AbstractAction
{

    use ActionTrait;

    /**
     * @param SchemaInterface $validationSchema
     * @param ErrorWriterInterface $errorWriter
     * @param Validator|null $validator
     */
    public function __construct(
        SchemaInterface $validationSchema,
        ErrorWriterInterface $errorWriter,
        Validator $validator = null
    ) {
        $this->init($validationSchema, $errorWriter, $validator);
    }

    /**
     * The main entry point, called by the routing component of your app
     *
     * @param ServerRequestInterface $req
     * @param ResponseInterface $res
     * @param array $urlParams E.g. dynamic parts of the url path
     * @return ResponseInterface
     */
    public function run(ServerRequestInterface $req, ResponseInterface $res, array $urlParams)
    {
        // first, we append the url parameters to the request
        $req = $req->withAttribute('url_params', $urlParams);
        return $this->handleRequest($req, $res);
    }

    /**
     * Please override this to implement more specific behaviour.
     *
     * {@inheritdoc}
     */
    protected function getInputData(ServerRequestInterface $req)
    {
        if (in_array(strtoupper($req->getMethod()), ['GET', 'HEAD'])) {
            return $req->getQueryParams();
        }
        $body = $req->getParsedBody();
        if (is_array($body)) {
            return $body;
        }
        return [];
    }
}

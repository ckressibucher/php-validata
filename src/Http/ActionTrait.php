<?php


namespace Ckr\Validata\Http;

use Ckr\Validata\Result;
use Ckr\Validata\Schema\SchemaInterface;
use Ckr\Validata\Validator;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Base functionality to use validator in controller actions.
 *
 */
trait ActionTrait
{

    /**
     * This value must be set before calling `run`.
     *
     * @var SchemaInterface
     */
    protected $validationSchema;

    /**
     * If not set, a default validator is instantiated.
     *
     * @var Validator
     */
    protected $validator;

    /**
     * Service to transform a response to have error information
     *
     * @var ErrorWriterInterface
     */
    protected $errorWriter;

    /**
     * Initialize this class. Intended to be used in the subclass's constructor
     *
     * @param SchemaInterface $validationSchema
     * @param Validator|null $validator
     */
    protected function init(
        SchemaInterface $validationSchema,
        ErrorWriterInterface $errorWriter,
        Validator $validator = null
    ) {
        $this->validationSchema = $validationSchema;
        $this->errorWriter = $errorWriter;
        $this->validator = $validator;
    }

    /**
     * The main entry point.
     *
     * Validates input data from the request, then calls further
     * methods to create a response, depending on the validation result.
     *
     * @param ServerRequestInterface $req
     * @param ResponseInterface $res
     * @return ResponseInterface
     */
    protected function handleRequest(ServerRequestInterface $req, ResponseInterface $res)
    {
        $input = $this->getInputData($req);
        $validator = isset($this->validator) ? $this->validator : new Validator();
        if (! isset($this->validationSchema)) {
            throw  MissingSchemaExcp::make(__CLASS__);
        }

        $validationResult = $validator->run($this->validationSchema, $input);

        if ($validationResult->hasErrors()) {
            return $this->handleDataErrors($validationResult, $req, $res);
        } else {
            $_data = $validationResult->hasValidData() ? $validationResult->getValidData() : [];
            return $this->handleRequestAction($_data, $req, $res);
        }
    }

    /**
     * The action method, invoked with successfully validated data
     *
     * @param array $validData
     * @param ServerRequestInterface $req
     * @param ResponseInterface $res
     * @return ResponseInterface
     */
    abstract protected function handleRequestAction(
        array $validData,
        ServerRequestInterface $req,
        ResponseInterface $res
    );

    /**
     * Returns the data used as input data for the controller action.
     *
     * This is the data passed to `_run` after it has been
     * validated successfully against the defined schema.
     *
     * @param ServerRequestInterface $req
     * @return array
     * @internal param Result $result The validation result
     */
    abstract protected function getInputData(ServerRequestInterface $req);

    /**
     * This method is called to ask for a "general" error message to
     * be included in the response of a invalid request.
     *
     * As the response can contain multiple errors, each with its own
     * error message, this one should be a more general message.
     *
     * @param Result $result
     * @return string
     */
    protected function getErrorMsg(Result $result)
    {
        return 'invalid input data';
    }

    protected function handleDataErrors(
        Result $result,
        ServerRequestInterface $req,
        ResponseInterface $res
    ) {
        return $this->respondWithError($this->getErrorMsg($result), $result, $req, $res);
    }

    protected function respondWithError(
        $msg,
        Result $validationResult,
        ServerRequestInterface $req,
        ResponseInterface $res
    ) {
        $code = (int) $res->getStatusCode();
        $code = $code !== 200 ? 400 : $code; // if code is not default (200), do not overwrite it
        return $this->errorWriter->makeErrResponse($msg, $code, $validationResult, $res);
    }
}

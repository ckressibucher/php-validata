<?php


namespace Ckr\Validata\Schema;


use Ckr\Validata\Result;
use Respect\Validation\Validatable;

/**
 * Checks the value of a scalar input value
 */
class Scalar implements SchemaInterface
{

    protected $validator;

    public function __construct(Validatable $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Validate given data against this schema
     *
     * @param mixed $data
     * @return Result
     */
    public function validate($data)
    {
        $errs = [];
        // TODO
        return new Result(null, $errs);
    }
}
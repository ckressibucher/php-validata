<?php


namespace Ckr\Validata\Schema;

use Ckr\Validata\Err\Err;
use Ckr\Validata\Err\ErrorMsg;
use Ckr\Validata\Err\HereLoc;
use Ckr\Validata\Err\LocationStack;
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
        if ($this->validator->validate($data)) {
            return Result::makeValid($data);
        }
        /* @var $exception \Exception */
        $exception = $this->validator->reportError($data);
        $err = new Err(
            LocationStack::fromLocation(HereLoc::getInstance()),
            new ErrorMsg(get_class($this->validator), $data, $exception->getMessage())
        );
        return Result::makeOnlyErrors([$err]);
    }
}

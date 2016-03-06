<?php


namespace Ckr\Validata;


use Ckr\Validata\Err\Err;

class Result
{

    /**
     * @var mixed
     */
    public $validData;

    /**
     * @var Err[]
     */
    public $errors;

    public function __construct($validData, array $errs)
    {
        $this->validData = $validData;
        $this->errors = $errs;
    }

}
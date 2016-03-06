<?php

namespace Ckr\Validata\Err;

class ErrorMsg
{

    /**
     * A unique identifier of the error. Maybe used to get a localized message
     * from somewhere...
     *
     * @var string
     */
    protected $id;

    /**
     * A human readable description of the error
     *
     * @var string
     */
    protected $desc;

    /**
     * Additional data, depending on the error
     *
     * @var array
     */
    protected $data;

    /**
     * The invalid input data
     *
     * @var mixed
     */
    protected $inputValue;

    public function __construct($id, $inputValue, $desc = '', $data = [])
    {
        $this->id = $id;
        $this->inputValue = $inputValue;
        $this->desc = $desc;
        $this->data = $data;
    }
}
<?php

namespace Ckr\Validata\Err;

/**
 * To represent a validation error, containing the message and location
 */
class Err
{

    /**
     * @var ErrorMsg
     */
    protected $msg;

    /**
     * @var LocationStack
     */
    protected $location;

    public function __construct(LocationStack $location, ErrorMsg $msg)
    {
        $this->location = $location;
        $this->msg = $msg;
    }

    /**
     * @return ErrorMsg
     */
    public function getMsg()
    {
        return $this->msg;
    }

    /**
     * @return LocationStack
     */
    public function getLocation()
    {
        return $this->location;
    }

}
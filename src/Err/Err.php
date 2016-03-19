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

    /**
     * Returns a new instance with the given Location prepended
     * to the current location stack
     *
     * @param LocationInterface $loc
     * @return Err
     */
    public function prependLocation(LocationInterface $loc)
    {
        $_loc = $this->getLocation()->prepend($loc);
        return new self($_loc, $this->getMsg());
    }
}

<?php

namespace Ckr\Validata\Err;

class Err
{

    /**
     * @var ErrorMsg
     */
    protected $msg;

    /**
     * @var LocationInterface
     */
    protected $location;

    public function __construct(LocationInterface $location, ErrorMsg $msg)
    {
    }
}
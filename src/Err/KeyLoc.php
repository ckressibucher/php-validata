<?php

namespace Ckr\Validata\Err;

class KeyLoc implements LocationInterface
{

    protected $key;

    /**
     * @param string $key
     */
    public function __construct($key)
    {
        $this->key = $key;
    }
}
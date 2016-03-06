<?php

namespace Ckr\Validata\Err;

class KeyLoc implements LocationInterface
{

    use LocationTrait;

    protected $key;

    /**
     * @param string $key
     */
    public function __construct($key)
    {
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return 'key';
    }

    /**
     * Returns a value describing the location.
     * The type of this value depends on the `getType` result
     *
     * @return string|int
     */
    public function getLocation()
    {
        return $this->key;
    }
}
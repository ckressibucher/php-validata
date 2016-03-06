<?php

namespace Ckr\Validata\Err;

class IndexLoc implements LocationInterface
{
    use LocationTrait;

    protected $index;

    /**
     * @param int $index
     */
    public function __construct($index)
    {
        $this->index = $index;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return 'index';
    }

    /**
     * Returns a value describing the location.
     * The type of this value depends on the `getType` result
     *
     * @return string|int
     */
    public function getLocation()
    {
        return $this->index;
    }
}
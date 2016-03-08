<?php

namespace Ckr\Validata\Err;

class IndexLoc implements LocationInterface
{
    use LocationTrait;

    const TYPE = 'index';

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
        return self::TYPE;
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
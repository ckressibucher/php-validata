<?php

namespace Ckr\Validata\Err;

class IndexLoc implements LocationInterface
{

    protected $index;

    /**
     * @param int $index
     */
    public function __construct($index)
    {
        $this->index = $index;
    }
}
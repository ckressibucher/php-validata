<?php

namespace Ckr\Validata\Err;

class LocationStack
{

    /**
     * @var LocationInterface[]
     */
    protected $stack = [];

    public function append(LocationInterface $loc)
    {
        $this->stack[] = $loc;
    }

    public function prepend(LocationInterface $loc)
    {
        array_unshift($this->stack, $loc);
    }

    public function getSimpleStack()
    {
        return array_map('strval', $this->stack);
    }
}
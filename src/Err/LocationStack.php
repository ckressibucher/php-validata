<?php

namespace Ckr\Validata\Err;

/**
 * A LocationStack represents the location of an error in
 * a multidimensional data structure. E.g. the `name` key in this JSON
 * {
 *   "root": [
 *      {"name": "this value is wrong"}
 *   ]
 * }
 * would result in a LocationStack of
 * [
 *   "key:root",
 *   "index:0",
 *   "key:name"
 * ]
 * Note that the deepest key ("name") is the last element of the stack.
 *
 * Instances are immutable unless you "hack" it with reflection or similar
 */
class LocationStack
{

    /**
     * @var LocationInterface[]
     */
    protected $stack;

    /**
     * @param LocationInterface[] $stack
     */
    public function __construct(array $stack = [])
    {
        $this->stack = $stack;
    }

    /**
     * Named constructor for an initial single location
     *
     * @param LocationInterface $loc
     * @return LocationStack
     */
    public static function fromLocation(LocationInterface $loc)
    {
        return new self([$loc]);
    }

    /**
     * Return a new `LocationStack` instance with an appended
     * `LocationInterface`
     *
     * @param LocationInterface $loc
     * @return self
     */
    public function append(LocationInterface $loc)
    {
        $stack = $this->stack;
        $stack[] = $loc;
        return new LocationStack($stack);
    }

    /**
     * Returns a new stack with a prepended Location
     *
     * @param LocationInterface $loc
     * @return LocationStack
     */
    public function prepend(LocationInterface $loc)
    {
        $stack = $this->stack;
        array_unshift($stack, $loc);
        return new LocationStack($stack);
    }

    /**
     * @return string[]
     */
    public function getSimpleStack()
    {
        return array_map('strval', $this->stack);
    }

    /**
     * @return LocationInterface[]
     */
    public function getStack()
    {
        return $this->stack;
    }
}
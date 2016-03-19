<?php

namespace Ckr\Validata\Err;

/**
 * Defines the location of a value (or error) in a data structure.
 * Only one level
 */
interface LocationInterface
{

    /**
     * @return string
     */
    public function getType();

    /**
     * Returns a value describing the location.
     * The type of this value depends on the `getType` result
     *
     * @return string|int
     */
    public function getLocation();

    /**
     * @return string
     */
    public function __toString();
}

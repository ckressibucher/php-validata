<?php


namespace Ckr\Validata\Schema;

use Ckr\Validata\Result;

/**
 * Defines the schema for a data class
 */
interface SchemaInterface
{

    /**
     * Validate given data against this schema
     *
     * @param mixed $data
     * @return Result
     */
    public function validate($data);
}
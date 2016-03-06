<?php


namespace Ckr\Validata;


use Ckr\Validata\Schema\SchemaInterface;

interface ValidatorInterface
{

    /**
     * Validates input data, returns a Result containing
     * valid data (on success) and a list of errors.
     *
     *
     * @param SchemaInterface $schema
     * @param mixed $data
     * @return Result
     */
    public function validate(SchemaInterface $schema, $data);
}
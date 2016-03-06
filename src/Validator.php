<?php


namespace Ckr\Validata;

use Ckr\Validata\Schema\SchemaInterface;

/**
 * Default validator
 */
class Validator implements ValidatorInterface
{

    /**
     * @param SchemaInterface $schema
     * @param mixed $data
     * @return Result
     */
    public function validate(SchemaInterface $schema, $data)
    {
        return $schema->validate($data);
    }
}
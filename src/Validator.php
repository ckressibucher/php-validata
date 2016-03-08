<?php


namespace Ckr\Validata;

use Ckr\Validata\Schema\SchemaInterface;

/**
 * Service class to validate data against a schema
 */
class Validator
{

    /**
     * @var Validator
     */
    private static $instance;

    /**
     * @param SchemaInterface $schema
     * @param mixed $data
     * @return Result
     */
    public function validate(SchemaInterface $schema, $data)
    {
        return $schema->validate($data);
    }

    public static function run(SchemaInterface $schema, $data)
    {
        return self::getInstance()->validate($schema, $data);
    }

    /**
     * @return Validator
     */
    private static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}
<?php


namespace Ckr\Validata\Schema;


use Ckr\Validata\Result;

class Map implements SchemaInterface
{

    /**
     * @var array
     */
    protected $map;


    public function __construct(array $schemas = [])
    {
        $this->map = $schemas;
    }

    /**
     * Defines a validation schema for the value of a property of the map
     *
     * @param string $key
     * @param SchemaInterface $validationSchema
     * @return self
     */
    public function property($key, SchemaInterface $validationSchema)
    {
        $this->map[$key] = $validationSchema;
    }

    /**
     * Validate given data against this schema
     *
     * @param array $data
     * @return Result
     */
    public function validate($data)
    {
        $errs = [];
        // TODO
        return new Result(null, $errs);
    }
}
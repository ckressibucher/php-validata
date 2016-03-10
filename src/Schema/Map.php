<?php


namespace Ckr\Validata\Schema;


use Ckr\Validata\Err\Err;
use Ckr\Validata\Err\KeyLoc;
use Ckr\Validata\Result;

/**
 * A schema for a map data structure
 */
class Map implements SchemaInterface
{

    /**
     * @var SchemaInterface[]
     */
    protected $map;


    /**
     * @param SchemaInterface[] $schemas An associative array with `SchemaInterface` values
     */
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
        $validData = [];
        foreach ($data as $key => $val) {
            if (! isset($this->map[$key])) {
                continue; // ignore values not defined in schema
            }
            $subSchema = $this->map[$key];
            $res = $subSchema->validate($val);

            // prepend the key to each error of the sub validation
            $_errs = array_map(function(Err $_err) use ($key) {
                return $_err->prependLocation(new KeyLoc($key));
            }, $res->getErrors());
            $errs = array_merge($errs, $_errs);

            if ($res->hasValidData()) {
                $validData[$key] = $res->getValidData();
            }
        }
        return Result::make($validData, $errs);
    }
}

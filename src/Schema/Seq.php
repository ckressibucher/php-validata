<?php


namespace Ckr\Validata\Schema;


use Ckr\Validata\Result;
use Respect\Validation\Validatable;

/**
 * Defines a list of items
 */
class Seq implements SchemaInterface
{

    /**
     * Error id denoting that the sequence's size is lower than the
     * defined minimum
     */
    const MIN_NUM_ERR = 'min_num_error';

    /**
     * Error id denoting that the sequence's size is greater than the
     * defined maximum
     */
    const MAX_NUM_ERR = 'max_num_error';

    /**
     * Validator for each item in the sequence
     *
     * @var Validatable
     */
    protected $itemValidator;

    /**
     * The minimum number of items expected.
     *
     * @var int
     */
    protected $minSize;

    /**
     * The maximum number of items allowed.
     * If -1, the maximum number is not restricted.
     *
     * @var int
     */
    protected $maxSize;

    /**
     * Seq constructor. See class properties for detailed info.
     *
     * @param Validatable $itemValidator
     * @param int $minSize
     * @param int $maxSize
     */
    public function __construct(Validatable $itemValidator, $minSize = 0, $maxSize = -1)
    {
        $this->itemValidator = $itemValidator;
        $this->minSize = $minSize;
        $this->maxSize = $maxSize;
    }

    public function validate($input)
    {
        $errs = [];
        // TODO validate each item
        return Result::makeValid([]);
    }
}
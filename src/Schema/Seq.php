<?php


namespace Ckr\Validata\Schema;


use Ckr\Validata\Err\Err;
use Ckr\Validata\Err\ErrorMsg;
use Ckr\Validata\Err\HereLoc;
use Ckr\Validata\Err\IndexLoc;
use Ckr\Validata\Err\LocationStack;
use Ckr\Validata\Result;

/**
 * Defines a list of items
 */
class Seq implements SchemaInterface
{

    /**
     * Denotes an error where the sequence's size is lower than the
     * defined minimum
     */
    const MIN_NUM_ERR = 'min_num_error';

    /**
     * Denotes an error where the sequence's size is greater than the
     * defined maximum
     */
    const MAX_NUM_ERR = 'max_num_error';

    const NOT_A_SEQ = 'not_a_sequence';

    /**
     * Validator for each item in the sequence
     *
     * @var SchemaInterface
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
     * @param SchemaInterface $itemValidator
     * @param int $minSize
     * @param int $maxSize
     */
    public function __construct(SchemaInterface $itemValidator, $minSize = 0, $maxSize = -1)
    {
        $this->itemValidator = $itemValidator;
        $this->minSize = $minSize;
        $this->maxSize = $maxSize;
    }

    public function validate($input)
    {
        $validData = [];

        list($initErrors, $traversedSeq) = $this->validateSeqLen($input);
        $errs = array_map(function(ErrorMsg $e) {
            return new Err(LocationStack::fromLocation(HereLoc::getInstance()), $e);
        }, $initErrors);

        if (!empty($errs)) {
            // if size is out of bound, or input is not a sequence type, stop here
            return Result::makeOnlyErrors($errs);
        }

        foreach ($traversedSeq as $idx => $item) {
            $res = $this->itemValidator->validate($item);
            $_errs = array_map(function(Err $_err) use ($idx) {
                return $_err->prependLocation(new IndexLoc($idx));
            }, $res->getErrors());
            $errs = array_merge($errs, $_errs);

            if ($res->hasValidData()) {
                $validData[] = $res->getValidData();
            }
        }
        return Result::make($validData, $errs);
    }

    /**
     * Returns an array of errors (may be empty) and the $input converted to an array
     *
     * @param $input
     * @return array
     */
    protected function validateSeqLen($input)
    {
        if (! is_array($input) && ! $input instanceof \Traversable) {
            $errMsg = new ErrorMsg(self::NOT_A_SEQ, $input, 'not a sequence');
            return [[$errMsg], []];
        }
        $fnCount = function() use ($input) {
            // return both, count and traversed sequence (as array) to support `Generator`s
            $traversedSeq = [];
            foreach($input as $i) $traversedSeq[] = $i;
            return [count($traversedSeq), $traversedSeq];
        };

        list($cnt, $traversedSeq) = $fnCount();
        $errs = [];
        if ($cnt < $this->minSize) {
            $errs[] = new ErrorMsg(self::MIN_NUM_ERR, $input);
        }
        if ($this->maxSize >= 0 && $cnt > $this->maxSize) {
            $errs[] = new ErrorMsg(self::MAX_NUM_ERR, $input);
        }
        return [$errs, $traversedSeq];
    }
}
<?php


namespace Ckr\Validata;


use Ckr\Validata\Err\Err;

class Result
{

    // constant used for constructing instances
    const NO_VALID_DATA = true;

    /**
     * If this is set, it means THERE IS valid data
     * @see hasValidData
     *
     * @var mixed
     */
    private $validData;

    /**
     * @var Err[]
     */
    private $errors;

    /**
     * @param mixed $validData   (ignored if $noValidData is set
     * @param Err[] $errs
     * @param bool  $noValidData Set to true to indicate that there is no valid data available
     *                           (This is required to dinstinguish "no valid data" from null or
     *                           similar values)
     */
    public function __construct($validData, array $errs, $noValidData = false)
    {
        if (! $noValidData) {
            $this->validData = $validData;
        }
        $this->errors = $errs;
    }

    /**
     * @param $validData
     * @return Result
     */
    public static function makeValid($validData)
    {
        return new self($validData, []);
    }

    /**
     * @param array $errors
     * @return Result
     */
    public static function makeOnlyErrors(array $errors)
    {
        return new self(null, $errors, self::NO_VALID_DATA);
    }

    /**
     * Get valid data if available. Otherwise given
     * $onNoValidData value is returned.
     * Be sure to check against that value !!
     *
     * @return mixed
     */
    public function getValidData($onNoValidData = null)
    {
        return $this->hasValidData() ? $this->validData : $onNoValidData;
    }

    /**
     * @return Err[]
     */
    public function getErrors()
    {
        return $this->errors;
    }

    public function hasValidData()
    {
        return isset($this->validData);
    }
}
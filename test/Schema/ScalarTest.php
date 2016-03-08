<?php


namespace Ckr\Validata\Test\Schema;


use Ckr\Validata\Schema\Scalar;
use Respect\Validation\Rules\IntVal;

class ScalarTest extends \PHPUnit_Framework_TestCase
{


    /**
     * @test
     */
    public function its_validate_returns_valid_data_on_success()
    {
        $value = '5';
        $result = $this->validateInteger($value);

        $this->assertEmpty($result->getErrors());
        $this->assertTrue($result->hasValidData());
        $this->assertSame($value, $result->getValidData());
    }

    /**
     * @test
     */
    public function its_validate_returns_no_valid_data_on_error()
    {
        $value = 'a';
        $result = $this->validateInteger($value);

        $this->assertFalse($result->hasValidData());
    }

    /**
     * @test
     */
    public function its_validate_returns_error_on_failed_validation()
    {
        $value = 'a';
        $result = $this->validateInteger($value);

        $this->assertCount(1, $result->getErrors());
    }

    /**
     * @param mixed $value
     * @return \Ckr\Validata\Result
     */
    private function validateInteger($value)
    {
        $validatable = new IntVal();

        $scalarSchema = new Scalar($validatable);
        return $scalarSchema->validate($value);
    }
}
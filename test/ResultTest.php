<?php


namespace Ckr\Validata\Test;


use Ckr\Validata\Err\Err;
use Ckr\Validata\Err\ErrorMsg;
use Ckr\Validata\Err\LocationStack;
use Ckr\Validata\Result;

class ResultTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function its_make_constructs_with_validData_and_errors()
    {
        $data = 'data';
        $errors = [new Err(
            new LocationStack(),
            new ErrorMsg('SOME_ERR', 'some bad input value')
        )];

        $result = Result::make($data, $errors);
        $this->assertTrue($result->hasValidData());
        $this->assertSame($data, $result->getValidData());
        $this->assertSame($errors, $result->getErrors());
    }

    /**
     * @test
     */
    public function its_makeValid_constructs_with_valid_data()
    {
        $data = 'data';

        $result = Result::makeValid($data);
        $this->assertTrue($result->hasValidData());
        $this->assertSame($data, $result->getValidData());
    }

    /**
     * @test
     */
    public function its_makeOnlyErrors_constructs_with_errors()
    {
        $errors = [new Err(
            new LocationStack(),
            new ErrorMsg('SOME_ERR', 'some bad input value')
        )];

        $result = Result::makeOnlyErrors($errors);
        $this->assertFalse($result->hasValidData());
        $this->assertSame($errors, $result->getErrors());
    }

    /**
     * @test
     */
    public function its_getValidData_returns_null_when_no_valid_data()
    {
        $errors = [new Err(
            new LocationStack(),
            new ErrorMsg('SOME_ERR', 'some bad input value')
        )];

        $result = Result::makeOnlyErrors($errors);
        $this->assertSame(null, $result->getValidData());
    }

    /**
     * @test
     */
    public function its_getValidData_returns_givenArgument_when_no_valid_data()
    {
        $errors = [new Err(
            new LocationStack(),
            new ErrorMsg('SOME_ERR', 'some bad input value')
        )];

        $result = Result::makeOnlyErrors($errors);
        $this->assertSame(false, $result->getValidData(false));
    }
}
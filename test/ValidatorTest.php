<?php

namespace Ckr\Validata\Test;

use Ckr\Validata\Result;
use Ckr\Validata\Schema\SchemaInterface;
use Ckr\Validata\Validator;

class ValidatorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function its_run_validates_data_against_schema()
    {
        $data = ['some' => 'value'];
        $result = Result::makeValid($data);

        $schema = $this->getMock(SchemaInterface::class);
        $schema->expects($this->once())
            ->method('validate')
            ->with($data)
            ->willReturn($result);

        $this->assertSame($result, Validator::run($schema, $data));
    }

    /**
     * @test
     */
    public function its_validate_tests_data_against_schema()
    {
        $data = ['some' => 'value'];
        $result = Result::makeValid($data);

        $schema = $this->getMock(SchemaInterface::class);
        $schema->expects($this->once())
            ->method('validate')
            ->with($data)
            ->willReturn($result);

        $validator = new Validator();
        $this->assertSame($result, $validator->validate($schema, $data));
    }

}
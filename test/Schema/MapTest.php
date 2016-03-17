<?php


namespace Ckr\Validata\Test\Schema;

use Ckr\Validata\Err\Err;
use Ckr\Validata\Err\ErrorMsg;
use Ckr\Validata\Err\HereLoc;
use Ckr\Validata\Err\LocationInterface;
use Ckr\Validata\Err\LocationStack;
use Ckr\Validata\Result;
use Ckr\Validata\Schema\Map;
use Ckr\Validata\Schema\SchemaInterface;

class MapTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function its_constructor_takes_an_initial_map()
    {
        $subSchema = $this->getMockForAbstractClass(SchemaInterface::class);
        $map = [
            'key' => $subSchema
        ];
        $mapSchema = new Map($map);

        $this->assertAttributeSame($map, 'map', $mapSchema);
    }

    /**
     * @test
     */
    public function its_property_sets_a_subkey_validation()
    {
        $subSchema = $this->getMockForAbstractClass(SchemaInterface::class);
        $mapSchema = new Map();
        $newSchema = $mapSchema->property('subkey', $subSchema);

        $this->assertAttributeEquals(['subkey' => $subSchema], 'map', $newSchema);
    }

    /**
     * @test
     */
    public function its_property_doesnt_mutate_original_instance()
    {
        $subSchema = $this->getMockForAbstractClass(SchemaInterface::class);
        $mapSchema = new Map();
        $mapSchema->property('subkey', $subSchema); // must not mutate $mapSchema

        $this->assertAttributeCount(0, 'map', $mapSchema);
    }

    /**
     * @test
     */
    public function its_validate_returns_the_validated_data_on_success()
    {
        $data = [
            'key' => 'value',
            'another' => 'value which is not defined in schema'
        ];
        // the subschema for value at 'key'
        $subSchemaResult = Result::makeValid('value');
        $subSchema = $this->getMockForAbstractClass(SchemaInterface::class);
        $subSchema->expects($this->any())
            ->method('validate')
            ->willReturn($subSchemaResult);

        $schema = new Map();
        $schema = $schema->property('key', $subSchema);

        $result = $schema->validate($data);
        $this->assertEmpty($result->getErrors());
        $this->assertEquals(['key' => 'value'], $result->getValidData());
    }

    public function its_validate_returns_valid_data_and_error_on_error()
    {
        // should also return the valid parts of the data when other parts fail
        $data = [
            'key' => 'value',
            'another' => 'some error'
        ];
        // the subschema for the VALID value at 'key'
        $validResult = Result::makeValid('value');
        $validSubSchema = $this->getMockForAbstractClass(SchemaInterface::class);
        $validSubSchema->expects($this->any())
            ->method('validate')
            ->willReturn($validResult);

        // the subschema for the INVALID value at 'another'
        $err = $this->makeErr('SOME_ERR');
        $invalidResult = Result::makeOnlyErrors([$err]);
        $invalidSubSchema = $this->getMockForAbstractClass(SchemaInterface::class);
        $invalidSubSchema->expects($this->any())
            ->method('validate')
            ->willReturn($invalidResult);

        $schema = new Map();
        $schema->property('key', $validSubSchema);
        $schema->property('another', $invalidSubSchema);

        // check valid data
        $result = $schema->validate($data);
        $this->assertEquals(['key' => 'value'], $result->getValidData());

        // check error (only one, check key and result)
        $this->assertCount(1, $result->getErrors());
        foreach ($result->getErrors() as $key => $resultErr) {
            $this->assertSame('another', $key);
            $this->assertSame($err, $resultErr);
        }
    }

    /**
     * @test
     */
    public function its_validate_prepends_location_to_error_stack()
    {
        $data = ['rootkey' => 'some bad input'];

        $err = $this->makeErr(
            'SCALAR_ERROR',
            $data['rootkey'],
            '',
            [],
            [HereLoc::getInstance()]
        );
        $subSchemaResult = Result::makeOnlyErrors([$err]);
        $subSchema = $this->getMockForAbstractClass(SchemaInterface::class);
        $subSchema->expects($this->any())
            ->method('validate')
            ->willReturn($subSchemaResult);

        $schema = new Map();
        $schema = $schema->property('rootkey', $subSchema);

        $result = $schema->validate($data);
        $errors = $result->getErrors();

        // the first (only) error should be the same as $err, but with 'key' Location prepended
        /* @var $resultError Err */
        $resultError = reset($errors);
        $this->assertSame('SCALAR_ERROR', $resultError->getMsg()->getId());
        $locStack = $resultError->getLocation();
        list($rootLoc, $subLoc) = $locStack->getSimpleStack();
        $this->assertSame('key:rootkey', $rootLoc);
        $this->assertSame('here:', $subLoc);
    }

    private function makeErr($id, $inputValue = 'dontcare', $desc = '', $data = [], $locations = [])
    {
        $errMsg = new ErrorMsg($id, $inputValue, $desc, $data);
        $stack = new LocationStack($locations);
        return new Err($stack, $errMsg);
    }
}
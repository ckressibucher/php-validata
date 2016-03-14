<?php


namespace Ckr\Validata\Test\Schema;


use Ckr\Validata\Err\Err;
use Ckr\Validata\Err\ErrorMsg;
use Ckr\Validata\Err\HereLoc;
use Ckr\Validata\Err\KeyLoc;
use Ckr\Validata\Err\LocationStack;
use Ckr\Validata\Result;
use Ckr\Validata\Schema\Seq;
use Ckr\Validata\Schema\SchemaInterface;

class SeqTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function its_validate_checks_min_length()
    {
        $data = []; // lenth 0
        $minLen = 1;

        $res = $this->runValidate($data, null, $minLen);

        $this->assertFalse($res->hasValidData());
        $this->assertOneError($res, Seq::MIN_NUM_ERR);
    }

    /**
     * @test
     */
    public function its_validate_checks_max_length()
    {
        $data = [1, 2]; // lenth 0
        $maxLen = 1;

        $res = $this->runValidate($data, null, 0, $maxLen);

        $this->assertFalse($res->hasValidData());
        $this->assertOneError($res, Seq::MAX_NUM_ERR);
    }

    /**
     * @test
     */
    public function its_validate_checks_works_with_arrays()
    {
        $data = [];
        $res = $this->runValidate($data);
        $this->assertTrue($res->hasValidData());
        $validData = $res->getValidData();
        $this->assertCount(0, $validData);
    }

    /**
     * use `Generator` instance as example of `Traversable`
     *
     * @test
     */
    public function its_validate_works_with_generators()
    {
        $fnMakeGenerator = function() {
            foreach (range(1, 2) as $i) {
                yield $i;
            }
        };
        $data = $fnMakeGenerator(); // Generator, which is an instance of Traversable
        $res = $this->runValidate($data);
        $this->assertTrue($res->hasValidData());
        $this->assertSame([1, 2], $res->getValidData());
    }

    /**
     * @test
     */
    public function its_validate_returns_error_if_input_is_scalar()
    {
        $data = 5; // not a sequence
        $res = $this->runValidate($data);
        $this->assertFalse($res->hasValidData());
        $this->assertOneError($res, Seq::NOT_A_SEQ);
    }

    /**
     * @test
     */
    public function its_validate_prepends_index_to_subschema_errors()
    {
        $inputData = ['bad value 0', 'good value', 'bad value 2']; // two errors, one ok, see callback mock

        $itemValidator = $this->getMockForAbstractClass(SchemaInterface::class);
        $itemValidator->expects($this->exactly(3))
            ->method('validate')
            ->will($this->returnCallback(function($_data) {
                if ($_data === 'good value') {
                    return Result::makeValid($_data);
                } else {
                    $_err = new Err(
                        LocationStack::fromLocation(HereLoc::getInstance()),
                        new ErrorMsg('ERR_X', $_data)
                    );
                    return Result::makeOnlyErrors([$_err]);
                }
            }));

        $result = $this->runValidate($inputData, $itemValidator);
        $this->assertEquals(['good value'], $result->getValidData());

        $errs = $result->getErrors();
        $this->assertCount(2, $errs);
        /* @var $firstErr Err */
        /* @var $secErr Err */
        list($firstErr, $secErr) = $errs;
        $this->assertSame(['index:0', 'here:'], $firstErr->getLocation()->getSimpleStack());
        $this->assertSame(['index:2', 'here:'], $secErr->getLocation()->getSimpleStack());
    }

    /**
     * @param mixed $input
     * @param SchemaInterface|null $itemValidator
     * @param int $minLen
     * @param int $maxLen
     * @return \Ckr\Validata\Result
     */
    private function runValidate($input, $itemValidator = null, $minLen = 0, $maxLen = -1)
    {
        $itemValidator = $itemValidator ?: $this->getPassThroughSchema();
        $seq = new Seq($itemValidator, $minLen, $maxLen);
        return $seq->validate($input);
    }

    private function assertOneError(Result $res, $expectedErrMsgId)
    {
        $errs = $res->getErrors();
        $this->assertCount(1, $errs);
        $e = reset($errs); /* @var $e \Ckr\Validata\Err\Err */
        $this->assertSame($expectedErrMsgId, $e->getMsg()->getId());
    }

    private function getPassThroughSchema()
    {
        $schema =  $this->getMockForAbstractClass(SchemaInterface::class);
        $schema->expects($this->any())
            ->method('validate')
            ->will($this->returnCallback(function($input) {
                return Result::makeValid($input);
            }));
        return $schema;
    }
}
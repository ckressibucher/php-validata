<?php


namespace Ckr\Validata\Test\Err;


use Ckr\Validata\Err\ErrorMsg;

class ErrMsgTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function it_has_expected_getters()
    {
        $id = 'ERR_X: expect a value of type {type}';
        $data = ['type' => 'number'];
        $errMsg = new ErrorMsg($id, 'four', 'a number was expected', $data);

        $this->assertSame($id, $errMsg->getId());
        $this->assertSame('four', $errMsg->getInputValue());
        $this->assertSame('a number was expected', $errMsg->getDesc());
        $this->assertSame($data, $errMsg->getData());
    }
}
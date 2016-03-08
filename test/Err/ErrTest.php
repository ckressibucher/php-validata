<?php


namespace Ckr\Validata\Test\Err;


use Ckr\Validata\Err\Err;
use Ckr\Validata\Err\ErrorMsg;
use Ckr\Validata\Err\LocationStack;

class ErrTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function it_has_expected_getters()
    {
        $locStack = new LocationStack();
        $msg = new ErrorMsg('ERR_X', 'abc');
        $err = new Err($locStack, $msg);

        $this->assertSame($locStack, $err->getLocation());
        $this->assertSame($msg, $err->getMsg());
    }
}
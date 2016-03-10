<?php


namespace Ckr\Validata\Test\Err;


use Ckr\Validata\Err\Err;
use Ckr\Validata\Err\ErrorMsg;
use Ckr\Validata\Err\HereLoc;
use Ckr\Validata\Err\KeyLoc;
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

    /**
     * @test
     */
    public function its_prependLocation_prepends_loc_to_current_locationStack()
    {
        $locStack = LocationStack::fromLocation(HereLoc::getInstance());
        $msg = new ErrorMsg('ERR_X', 'abc');
        $err = new Err($locStack, $msg);

        $newLoc = new KeyLoc('x');
        $newErr = $err->prependLocation($newLoc);

        $this->assertSame($err->getMsg(), $newErr->getMsg());
        $this->assertSame(
            ['key:x', 'here:'],
            $newErr->getLocation()->getSimpleStack()
        );
    }
}
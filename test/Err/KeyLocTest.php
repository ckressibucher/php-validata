<?php


namespace Ckr\Validata\Test\Err;


use Ckr\Validata\Err\KeyLoc;

class KeyLocTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function it_has_expected_getters()
    {
        $loc = new KeyLoc('thekey');
        $this->assertSame(KeyLoc::TYPE, $loc->getType());
        $this->assertSame('thekey', $loc->getLocation());
    }
}
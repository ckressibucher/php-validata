<?php


namespace Ckr\Validata\Test\Err;


use Ckr\Validata\Err\HereLoc;

class HereLocTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function it_is_a_singleton()
    {
        $inst = HereLoc::getInstance();
        $inst2 = HereLoc::getInstance();

        $this->assertSame($inst, $inst2);
    }

    /**
     * @test
     */
    public function it_returns_type_here()
    {
        $this->assertSame(HereLoc::TYPE, HereLoc::getInstance()->getType());
    }

    /**
     * @test
     */
    public function it_returns_emptyString_as_location()
    {
        $this->assertSame('', HereLoc::getInstance()->getLocation());
    }
}
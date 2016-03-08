<?php


namespace Ckr\Validata\Test\Err;


use Ckr\Validata\Err\IndexLoc;

class IndexLocTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function it_has_expected_getters()
    {
        $indexLoc = new IndexLoc(5);
        $this->assertSame(IndexLoc::TYPE, $indexLoc->getType());
        $this->assertSame(5, $indexLoc->getLocation());
    }
}
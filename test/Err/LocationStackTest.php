<?php


namespace Ckr\Validata\Test\Err;


use Ckr\Validata\Err\IndexLoc;
use Ckr\Validata\Err\KeyLoc;
use Ckr\Validata\Err\LocationStack;
use Ckr\Validata\Err\LocationInterface;

class LocationStackTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function its_append_adds_a_location_on_top()
    {
        $oldLoc = new KeyLoc('root');
        $newLoc = new IndexLoc(4);
        $stack = LocationStack::fromLocation($oldLoc);

        $newStack = $stack->append($newLoc);

        $expected = ['key:root', 'index:4'];
        $this->assertEquals($expected, $newStack->getSimpleStack());
    }

    /**
     * @test
     */
    public function its_prepend_adds_a_location_on_index_zero()
    {
        $newLoc = new KeyLoc('root');
        $oldLoc = new IndexLoc(4);
        $stack = LocationStack::fromLocation($oldLoc);

        $newStack = $stack->prepend($newLoc);

        $simpleStack = $newStack->getSimpleStack();
        $this->assertEquals('key:root', $simpleStack[0]);
    }

    /**
     * @test
     */
    public function its_getSimpleStack_returns_an_array_of_stringified_locations()
    {
        $loc = new KeyLoc('somekey');
        $stack = LocationStack::fromLocation($loc);

        $this->assertEquals(['key:somekey'], $stack->getSimpleStack());
    }

    /**
     * @test
     */
    public function its_getStack_returns_the_array_of_location_objects()
    {
        $loc = new KeyLoc('somekey');
        $stack = LocationStack::fromLocation($loc);

        $stackArr = $stack->getStack();
        $this->assertInstanceOf(LocationInterface::class, reset($stackArr));
    }
}
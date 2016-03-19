<?php


namespace Ckr\Validata\Test\Http;

use Ckr\Validata\Http\MissingSchemaExcp;

class MissingSchemaExcpTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function it_stores_class_property()
    {
        $e = MissingSchemaExcp::make(\stdClass::class);
        $this->assertSame('stdClass', $e->clazz);
    }

    /**
     * @test
     */
    public function it_returns_expected_message()
    {
        $e = MissingSchemaExcp::make(\stdClass::class);
        $expectedMsg = sprintf('No schema defined in class=%s', \stdClass::class);
        $this->assertSame($expectedMsg, $e->getMessage());
    }
}
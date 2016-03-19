<?php


namespace Ckr\Validata\Test\Http;

use Ckr\Validata\Err\Err;
use Ckr\Validata\Err\ErrorMsg;
use Ckr\Validata\Err\HereLoc;
use Ckr\Validata\Err\KeyLoc;
use Ckr\Validata\Err\LocationStack;
use Ckr\Validata\Http\JsonErrorWriter;
use Ckr\Validata\Result;
use Ckr\Validata\Test\Util\ResponseMock;
use Psr\Http\Message\ResponseInterface;

class JsonErrorWriterTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function it_adds_contentType_header()
    {
        $writer = new JsonErrorWriter();
        $res = new ResponseMock();
        $newRes = $writer->makeErrResponse(
            'validation error',
            400,
            Result::makeValid(''),
            $res
        );
        $this->assertSame('application/json', $newRes->getHeaderLine('Content-Type'));
    }

    /**
     * @test
     */
    public function it_writes_error_message_to_error_field()
    {
        $writer = new JsonErrorWriter('the_error_field');
        $res = $writer->makeErrResponse(
            'my error',
            400,
            Result::makeValid(''),
            new ResponseMock()
        );
        $data = json_decode($res->getBody()->__toString(), true);
        $this->assertSame('my error', $data['the_error_field']);
    }

    /**
     * @test
     */
    public function it_throws_exception_if_body_is_not_empty()
    {
        $writer = new JsonErrorWriter();
        $response = new ResponseMock();
        $response->getBody()->write('abc');

        $this->expectException(\RuntimeException::class);
        $writer->makeErrResponse(
            'error',
            400,
            Result::makeOnlyErrors([]),
            $response
        );
    }

    /**
     * @test
     */
    public function it_adds_error_info()
    {
        $writer = new JsonErrorWriter('error', 'error_info');
        $stack = LocationStack::fromLocation(HereLoc::getInstance())
            ->prepend(new KeyLoc('root'));
        $errors = [
            new Err($stack, new ErrorMsg('ERR_X', 'value'))
        ];
        $res = $writer->makeErrResponse(
            'error',
            400,
            Result::makeOnlyErrors($errors),
            new ResponseMock()
        );

        $data = json_decode($res->getBody()->__toString(), true);
        $firstError = $data['error_info'][0];
        $stack = $firstError['stack'];

        $this->assertSame('ERR_X', $firstError['error_id']);
        $this->assertSame('', $firstError['error_desc']);
        $this->assertCount(0, $firstError['error_data']);
        $this->assertSame('value', $firstError['input_value']);

        $this->assertSame('key', $stack[0]['type']);
        $this->assertSame('root', $stack[0]['location']);
        $this->assertSame('here', $stack[1]['type']);
        $this->assertSame('', $stack[1]['location']);
    }
}

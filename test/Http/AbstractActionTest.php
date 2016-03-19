<?php


namespace Ckr\Validata\Test\Http;

use Ckr\Validata\Http\AbstractAction;
use Ckr\Validata\Schema\SchemaInterface;
use Ckr\Validata\Http\ErrorWriterInterface;
use Ckr\Validata\Test\Util\ResponseMock;
use Ckr\Validata\Test\Util\ServerRequestMock;
use Ckr\Validata\Validator;

class AbstractActionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function it_initializes_validatorSchema()
    {
        $schema = $this->getMock(SchemaInterface::class);
        $errWriter = $this->getMock(ErrorWriterInterface::class);
        $action = $this->getMockForAbstractClass(AbstractAction::class, [$schema, $errWriter]);

        $this->assertAttributeEquals($schema, 'validationSchema', $action);
    }

    /**
     * @test
     */
    public function it_initializes_errorWriter()
    {
        $schema = $this->getMock(SchemaInterface::class);
        $errWriter = $this->getMock(ErrorWriterInterface::class);
        $action = $this->getMockForAbstractClass(AbstractAction::class, [$schema, $errWriter]);

        $this->assertAttributeEquals($errWriter, 'errorWriter', $action);
    }

    /**
     * @test
     */
    public function it_initializes_validator()
    {
        $schema = $this->getMock(SchemaInterface::class);
        $errWriter = $this->getMock(ErrorWriterInterface::class);
        $validator = new Validator();
        $action = $this->getMockForAbstractClass(AbstractAction::class, [$schema, $errWriter, $validator]);

        $this->assertAttributeEquals($validator, 'validator', $action);
    }

    /**
     * @test
     */
    public function its_run_adds_urlParams_to_request()
    {
        $req = new ServerRequestMock();
        $urlParams = ['product' => '36'];
        $res = new ResponseMock();

        $schema = $this->getMock(SchemaInterface::class);
        $errWriter = $this->getMock(ErrorWriterInterface::class);

        /* @var $action \PHPUnit_Framework_MockObject_MockObject|AbstractAction */
        $action = $this->getMockBuilder(AbstractAction::class)
            ->setConstructorArgs([$schema, $errWriter])
            ->setMethods(['handleRequest', 'handleRequestAction'])
            ->getMock();

        $action->expects($this->once())
            ->method('handleRequest')
            ->with(
                $this->callback(function ($request) {
                    $urlParams = $request->getAttribute('url_params');
                    return isset($urlParams['product']) && $urlParams['product'] === '36';
                }),
                $this->anything()
            );

        $action->run($req, $res, $urlParams);
    }

    /**
     * @test
     */
    public function its_getInputData_returns_query_params_on_GET()
    {
        $input = ['q' => 'search'];
        $req = (new ServerRequestMock())->withQueryParams($input)
            ->withMethod('GET');
        $actual = $this->runGetInputData($req);
        $this->assertSame('search', $actual['q']);
    }

    /**
     * @test
     */
    public function its_getInputData_returns_query_params_on_HEAD()
    {
        $input = ['q' => 'search...'];
        $req = (new ServerRequestMock())->withQueryParams($input)
            ->withMethod('HEAD');
        $actual = $this->runGetInputData($req);
        $this->assertSame('search...', $actual['q']);
    }

    /**
     * @test
     */
    public function its_getInput_returns_only_urlparams_on_empty_POST()
    {
        $req = (new ServerRequestMock())->withParsedBody('')
            ->withMethod('POST');

        $expected = ['url' => []];
        $actual = $this->runGetInputData($req);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function its_getInput_returns_parsed_body_array_on_POST()
    {
        $bodyData = ['name' => 'rosa', 'pass' => 'rosa124'];
        $req = (new ServerRequestMock())->withParsedBody($bodyData)
            ->withMethod('POST');

        $actual = $this->runGetInputData($req);

        $this->assertSame('rosa', $actual['name']);
        $this->assertSame('rosa124', $actual['pass']);
    }

    /**
     * @test
     */
    public function its_getInput_returns_only_urlparams_on_POST_object_data()
    {
        // We cannot handle objects, as we do not know anything about them.
        // For that reason, we just return an empty array per default and
        // let the subclass implement specific behaviour
        $bodyData = (object) ['name' => 'rosa', 'pass' => 'rosa124'];
        $req = (new ServerRequestMock())->withParsedBody($bodyData)
            ->withMethod('POST');
        $actual = $this->runGetInputData($req);
        $this->assertEquals(['url' => []], $actual);
    }

    /**
     * @test
     */
    public function its_getInput_returns_url_params()
    {
        $req = (new ServerRequestMock())
            ->withAttribute('url_params', ['product' => '36'])
            ->withMethod('GET');

        $actual = $this->runGetInputData($req);
        $this->assertSame('36', $actual['url']['product']);
    }

    /**
     * @param $request
     * @return array
     */
    private function runGetInputData($request)
    {
        $action = $this->getMockBuilder(AbstractAction::class)
            ->disableOriginalConstructor()
            ->setMethods(['handleRequestAction'])
            ->getMock();

        $reflMethod = new \ReflectionMethod(AbstractAction::class, 'getInputData');
        $reflMethod->setAccessible(true);
        return $reflMethod->invokeArgs($action, [$request]);
    }
}

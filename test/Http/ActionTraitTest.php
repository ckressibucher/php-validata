<?php


namespace Ckr\Validata\Test\Http;

use Ckr\Validata\Err\Err;
use Ckr\Validata\Err\ErrorMsg;
use Ckr\Validata\Err\HereLoc;
use Ckr\Validata\Err\LocationStack;
use Ckr\Validata\Http\ActionTrait;
use Ckr\Validata\Http\ErrorWriterInterface;
use Ckr\Validata\Http\MissingSchemaExcp;
use Ckr\Validata\Schema\SchemaInterface;
use Ckr\Validata\Http\JsonErrorWriter;
use Ckr\Validata\Result;
use Ckr\Validata\Schema\Map;
use Ckr\Validata\Schema\Scalar;
use Ckr\Validata\Test\Util\ResponseMock;
use Ckr\Validata\Validator;
use PHPUnit_Framework_MockObject_MockObject;
use Respect\Validation\Rules\Alpha;
use Respect\Validation\Rules\AlwaysValid;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class ActionTraitTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function it_passes_valid_request_data_to_handleRequestAction()
    {
        $inputData = ['name' => 'Rapunzel'];
        $result = Result::makeValid($inputData);

        $action = $this->mockWithResult($result, ['handleRequestAction', 'handleDataErrors']);
        $action->expects($this->once())
            ->method('handleRequestAction')
            ->with($inputData, $this->anything(), $this->anything());

        $action->expects($this->never())
            ->method('handleDataErrors');

        $action->run(
            $this->getMock(ServerRequestInterface::class),
            $this->getMock(ResponseInterface::class)
        );
    }

    /**
     * @test
     */
    public function it_calls_handleDataErrors_on_invalid_data()
    {
        $errLoc = LocationStack::fromLocation(HereLoc::getInstance());
        $result = Result::makeOnlyErrors([
            new Err(
                $errLoc,
                new ErrorMsg('ERR_X', 'input')
            )
        ]);
        $action = $this->mockWithResult($result, ['handleRequestAction', 'handleDataErrors']);
        $action->expects($this->never())
            ->method('handleRequestAction');

        $action->expects($this->once())
            ->method('handleDataErrors')
            ->with($result, $this->anything(), $this->anything());

        $action->run(
            $this->getMock(ServerRequestInterface::class),
            $this->getMock(ResponseInterface::class)
        );
    }

    /**
     * @test
     */
    public function it_throws_excep_if_no_schema_defined()
    {
        $action = $this->getActionTraitMock($this->getMock(SchemaInterface::class));

        // use reflection to unset schema
        $reflProp = new \ReflectionProperty(ActionTraitImpl::class, 'validationSchema');
        $reflProp->setAccessible(true);
        $reflProp->setValue($action, null);

        $this->expectException(MissingSchemaExcp::class);
        $action->run(
            $this->getMock(ServerRequestInterface::class),
            $this->getMock(ResponseInterface::class)
        );
    }

    /**
     * @test
     */
    public function it_passes_errors_to_errorWriter()
    {
        $err = $this->getMock(Err::class, [], [], '',  false);
        $result = Result::makeOnlyErrors([$err]);

        $errorWriter = $this->getMock(ErrorWriterInterface::class, ['makeErrResponse']);
        $errorWriter->expects($this->once())
            ->method('makeErrResponse')
            ->with(
                'invalid input data', // main error message, see ActionTrait::getErrorMsg
                400, // http status code
                $result,
                $this->anything()
            );

        $action = $this->mockWithResult(
            $result,
            null,
            $errorWriter
        );

        $action->run(
            $this->getMock(ServerRequestInterface::class),
            (new ResponseMock())->withStatus(200) // should be changed to 400
        );
    }

    /**
     * @param $schema
     * @param $errWriter
     * @param array|null $methods
     * @param null $validator
     * @return PHPUnit_Framework_MockObject_MockObject|ActionTraitImpl
     */
    private function getActionTraitMock($schema, $errWriter = null, $methods = null, $validator = null)
    {
        $errWriter = $errWriter ?: new JsonErrorWriter();
        $constrArgs = [$schema, $errWriter, $validator];

        return $this->getMock(
            ActionTraitImpl::class,
            $methods,
            $constrArgs
        );
    }

    /**
     * @param Result $result
     * @param array|null $methods
     * @param ErrorWriterInterface|null $errWriter
     * @return ActionTraitImpl|PHPUnit_Framework_MockObject_MockObject
     */
    private function mockWithResult(Result $result, $methods = [], $errWriter = null)
    {
        $validator = $this->getMock(Validator::class, ['validate']);
        $validator->expects($this->any())
            ->method('validate')
            ->willReturn($result);

        $schema = $this->getMock(SchemaInterface::class);
        $errWriter = $errWriter ?: $this->getMock(ErrorWriterInterface::class);
        return $this->getActionTraitMock($schema, $errWriter, $methods, $validator);
    }
}

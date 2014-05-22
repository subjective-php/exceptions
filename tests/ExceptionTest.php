<?php
namespace Chadicus\Util;

/**
 * Unit tests for the \Chadicus\Util\Exception class.
 *
 * @coversDefaultClass \Chadicus\Util\Exception
 */
final class ExceptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Verify basic functionality of getBaseException().
     *
     * @test
     * @covers ::getBaseException
     *
     * @return void
     */
    public function getBaseException()
    {
        $a = new \ErrorException('exception a');
        $b = new \InvalidArgumentException('exception b', 0, $a);
        $c = new \Exception('exception c', 0, $b);

        $this->assertSame($a, Exception::getBaseException($c));
        $this->assertSame($a, Exception::getBaseException($b));
        $this->assertSame($a, Exception::getBaseException($a));
    }

    /**
     * Verify behavior of getBaseException() when there is no previous exception.
     *
     * @test
     * @covers ::getBaseException
     *
     * @return void
     */
    public function getBaseExceptionNoPrevious()
    {
        $e = new \Exception();
        $this->assertSame($e, Exception::getBaseException($e));
    }

    /**
     * Verifies basic behavior of raise().
     *
     * @test
     * @covers ::raise
     *
     * @return void
     */
    public function raise()
    {
        set_error_handler('\Chadicus\Util\Exception::raise');
        try {
            trigger_error('test', E_USER_NOTICE);
        } catch (\ErrorException $e) {
            $this->assertSame('test', $e->getMessage());
            $this->assertSame(0, $e->getCode());
            $this->assertSame(E_USER_NOTICE, $e->getSeverity());
            $this->assertSame((__LINE__) - 5, $e->getLine());
            $this->assertSame(__FILE__, $e->getFile());
        }

        restore_error_handler();
    }

    /**
     * Verifies raise() returns false when error reporting is disabled.
     *
     * @test
     * @covers ::raise
     *
     * @return void
     */
    public function raiseErrorReportingDisabled()
    {
        $restoreLevel = error_reporting(0);
        $this->assertFalse(Exception::raise(E_USER_NOTICE, 'test', __FILE__, __LINE__));
        error_reporting($restoreLevel);
    }

    /**
     * Verify basic behavior of toArray().
     *
     * @test
     * @covers ::toArray
     *
     * @return void
     */
    public function toArray()
    {
        $expectedLine = __LINE__ + 1;
        $result = Exception::toArray(new \RuntimeException('a message', 21));

        $expected = array(
            'type' => 'RuntimeException',
            'message' => 'a message',
            'code' => 21,
            'file' => __FILE__,
            'line' => $expectedLine,
            'trace' => $result['trace'],
        );

        $this->assertSame($expected, $result);
    }

    /**
     * Verifies basic behavior of fromLastError().
     *
     * @test
     * @covers ::fromLastError
     *
     * @return void
     */
    public function fromLastError()
    {
        $restoreLevel = error_reporting(0);
        trigger_error('test', E_USER_NOTICE);
        $exception = Exception::fromLastError();
        $this->assertSame('test', $exception->getMessage());
        $this->assertSame(0, $exception->getCode());
        $this->assertSame(E_USER_NOTICE, $exception->getSeverity());
        $this->assertSame((__LINE__) - 5, $exception->getLine());
        $this->assertSame(__FILE__, $exception->getFile());
        error_reporting($restoreLevel);
    }
}

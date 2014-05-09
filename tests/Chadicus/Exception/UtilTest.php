<?php
namespace Chadicus\Exception;

/**
 * @coversDefaultClass \Chadicus\Exception\Util
 */
final class UtilTest extends \PHPUnit_Framework_TestCase
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

        $this->assertSame($a, Util::getBaseException($c));
        $this->assertSame($a, Util::getBaseException($b));
        $this->assertSame($a, Util::getBaseException($a));
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
        $this->assertSame($e, Util::getBaseException($e));
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
        set_error_handler('\Chadicus\Exception\Util::raise');
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
        $this->assertFalse(Util::raise(E_USER_NOTICE, 'test', __FILE__, __LINE__));
        error_reporting($restoreLevel);
    }
}

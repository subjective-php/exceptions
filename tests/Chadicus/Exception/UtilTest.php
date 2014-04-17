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
}

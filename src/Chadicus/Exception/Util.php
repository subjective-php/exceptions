<?php
namespace Chadicus\Exception;

/**
 * Static utility class for exceptions
 */
class Util
{
    /**
     * Returns the Exception that is the root cause of one or more subsequent exceptions.
     *
     * @return Exception
     */
    final public static function getBaseException(\Exception $exception)
    {
        while ($exception->getPrevious() !== null) {
            $exception = $exception->getPrevious();
        }

        return $exception;
    }
}

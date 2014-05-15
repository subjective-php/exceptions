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

    /**
     * Throws a new \ErrorException based on the error information provided.
     * To be used as a callback for {@see set_error_handler()}
     *
     * @return bool false
     *
     * @throws \ErrorException
     */
    final public static function raise($level, $message, $file = null, $line = null)
    {
        if (error_reporting() === 0) {
            return false;
        }

        throw new \ErrorException($message, 0, $level, $file, $line);
    }

    /**
     * Converts the given Exception to an array.
     *
     * @param \Exception $exception The exception to convert.
     *
     * @return array
     */
    final public static function toArray(\Exception $exception)
    {
        return array(
            'type' => get_class($exception),
            'message' => $exception->getMessage(),
            'code' => $exception->getCode(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
        );
    }
}

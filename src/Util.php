<?php
namespace Chadicus\Exception;

/**
 * Static utility class for exceptions.
 */
class Util
{
    /**
     * Returns the Exception that is the root cause of one or more subsequent exceptions.
     *
     * @param \Exception $exception The exception of which to find a base exception.
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
     *
     * @param integer $level   The level of the error raised.
     * @param string  $message The error message.
     * @param string  $file    The filename from which the error was raised.
     * @param integer $line    The line number at which the error was raised.
     *
     * @return bool false
     *
     * @throws \ErrorException Thrown based on information given in parameters.
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

    /**
     * Creates an ErrorException based on the error from error_get_last().
     *
     * @return \ErrorException
     */
    final public static function fromLastError()
    {
        $error = error_get_last();
        return new \ErrorException($error['message'], 0, $error['type'], $error['file'], $error['line']);
    }
}

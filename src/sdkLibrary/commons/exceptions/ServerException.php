<?php

namespace Inter\Sdk\sdkLibrary\commons\exceptions;

use Inter\Sdk\sdkLibrary\commons\models\Error;

/**
 * The ServerException class is a custom exception that extends SdkException.
 *
 * This exception is thrown to indicate errors that occur on the server side,
 * typically related to issues encountered while processing requests.
 * It allows for capturing specific error details related to server operations.
 */
class ServerException extends SdkException
{
    /**
     * Constructs a new ServerException with the specified detail message and
     * error information.
     *
     * @param string $message The detail message that explains the reason for the exception.
     * @param Error $error An Error object containing additional error details.
     */
    public function __construct(string $message, Error $error)
    {
        parent::__construct($message, $error);
    }
}

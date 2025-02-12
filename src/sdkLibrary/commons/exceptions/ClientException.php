<?php

namespace Inter\Sdk\sdkLibrary\commons\exceptions;

use Inter\Sdk\sdkLibrary\commons\models\Error;

/**
 * The ClientException class is a custom exception that extends SdkException.
 *
 * This exception is thrown to indicate specific errors related to client operations
 * within the SDK. It can be used to handle exceptions that arise during client interactions,
 * providing a way to include additional error information.
 */
class ClientException extends SdkException
{
    /**
     * Constructs a new ClientException with the specified detail message and
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

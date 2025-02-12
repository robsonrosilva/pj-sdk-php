<?php

namespace Inter\Sdk\sdkLibrary\commons\exceptions;

use Exception;
use Inter\Sdk\sdkLibrary\commons\models\Error;

/**
 * The SdkException class is a base exception class for the SDK.
 *
 * This exception is thrown to indicate general errors that occur within the SDK.
 * It encapsulates an error object that contains additional details about the exception.
 */
class SdkException extends Exception
{
    /**
     * An Error object containing additional error details.
     *
     * @var Error
     */
    private Error $error;

    /**
     * Constructs a new SdkException with the specified detail message and error information.
     *
     * @param string $message The detail message that explains the reason for the exception.
     * @param Error $error An Error object containing additional error details.
     */
    public function __construct(string $message, Error $error)
    {
        parent::__construct($message);
        $this->error = $error;
    }

    /**
     * Get the Error object associated with this exception.
     *
     * @return Error
     */
    public function getError(): Error
    {
        return $this->error;
    }

    /**
     * Set the Error object associated with this exception.
     *
     * @param Error $error
     */
    public function setError(Error $error): void
    {
        $this->error = $error;
    }
}

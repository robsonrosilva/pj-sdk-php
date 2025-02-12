<?php

namespace Inter\Sdk\sdkLibrary\commons\exceptions;

use Inter\Sdk\sdkLibrary\commons\models\Error;

/**
 * The CertificateException class is a custom exception that extends SdkException.
 *
 * This exception is thrown to indicate errors related to SSL certificates, such as invalid,
 * expired, or not found certificates. It provides the ability to include additional error
 * details with the exception.
 */
class CertificateException extends SdkException
{
    /**
     * Constructs a new CertificateException with the specified detail message and
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

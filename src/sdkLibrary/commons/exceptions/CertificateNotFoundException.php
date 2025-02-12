<?php

namespace Inter\Sdk\sdkLibrary\commons\exceptions;

use Inter\Sdk\sdkLibrary\commons\models\Error;
use Inter\Sdk\sdkLibrary\commons\structure\Constants;

/**
 * The CertificateNotFoundException class is a custom exception that extends ClientException.
 *
 * This exception is thrown when an SSL certificate is not found. It provides a specific message
 * indicating that the requested certificate is missing and includes details about the certificate
 * name and a reference to relevant documentation.
 */
class CertificateNotFoundException extends ClientException
{
    /**
     * Constructs a new CertificateNotFoundException with the specified certificate name.
     *
     * @param string $certificate The name of the certificate that was not found.
     */
    public function __construct(string $certificate)
    {
        $error = Error::builder()
            ->title("Certificate not found")
            ->detail(sprintf("Certificate not found: %s. Consult %s.",
                $certificate,
                Constants::DOC_CERTIFICATE))
            ->build();

        parent::__construct("Certificate not found", $error);
    }
}

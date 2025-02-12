<?php

namespace Inter\Sdk\sdkLibrary\commons\exceptions;

use Inter\Sdk\sdkLibrary\commons\models\Error;
use Inter\Sdk\sdkLibrary\commons\structure\Constants;

/**
 * The CertificateExpiredException class is a custom exception that extends ClientException.
 *
 * This exception is thrown when an SSL certificate has expired. It provides a specific message
 * indicating that the certificate is no longer valid and includes details about the expiration date
 * and a reference to relevant documentation.
 */
class CertificateExpiredException extends ClientException
{
    /**
     * Constructs a new CertificateExpiredException with the specified expiration date.
     *
     * @param string $notAfter The date when the certificate expired.
     */
    public function __construct(string $notAfter)
    {
        $error = Error::builder()
            ->title("Certificate expired")
            ->detail(sprintf("Certificate expired in %s. Consult %s.",
                $notAfter,
                Constants::DOC_CERTIFICATE))
            ->build();

        parent::__construct("Certificate expired", $error);
    }
}

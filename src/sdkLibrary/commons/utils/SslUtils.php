<?php

namespace Inter\Sdk\sdkLibrary\commons\utils;

use Inter\Sdk\sdkLibrary\commons\exceptions\CertificateExpiredException;
use Inter\Sdk\sdkLibrary\commons\exceptions\CertificateNotFoundException;
use RuntimeException;

class SslUtils
{
    /**
     * Convert PFX to PEM format.
     *
     * @param string $pfxFile
     * @param string $password
     * @return array
     * @throws CertificateNotFoundException
     */
    public static function convertPfxToPem(string $pfxFile, string $password): array
    {
        if (!file_exists($pfxFile)) {
            throw new CertificateNotFoundException($pfxFile);
        }

        $pfxContent = file_get_contents($pfxFile);

        if ($pfxContent === false) {
            throw new CertificateNotFoundException($pfxFile);
        }
        $certInfo = [];
        if (!openssl_pkcs12_read($pfxContent, $certInfo, $password)) {
            throw new RuntimeException("Failed to read the PFX file. Check if the password is correct.");
        }

        $privateKey = $certInfo['pkey'];
        $certificate = $certInfo['cert'];

        return [
            'private_key' => $privateKey,
            'certificate' => $certificate
        ];
    }

    /**
     * Get certificate and key file names.
     *
     * @param string $privateKey
     * @param string $certificate
     * @return array
     */
    public static function getCertKeyName(string $privateKey, string $certificate): array
    {
        $tempDir = sys_get_temp_dir();
        $pemPrivateKeyPath = tempnam($tempDir, 'private_');
        $pemCertificatePath = tempnam($tempDir, 'cert_');

        file_put_contents($pemPrivateKeyPath, $privateKey);
        file_put_contents($pemCertificatePath, $certificate);

        return [
            'private_key_path' => $pemPrivateKeyPath,
            'certificate_path' => $pemCertificatePath
        ];
    }

    /**
     * Check if the certificate is expiring soon.
     *
     * @param string $certificate PEM formatted certificate
     * @param int $days Number of days to check against
     * @return array [bool $isExpiringSoon, int $daysUntilExpiration]
     * @throws CertificateExpiredException
     */
    public static function isCertificateExpiringSoon(string $certificate, int $days): array
    {
        $certDetails = openssl_x509_parse($certificate);

        if ($certDetails === false) {
            throw new \RuntimeException("Invalid certificate format.");
        }

        $expirationDate = $certDetails['validTo_time_t'];
        $currentDate = time();

        if ($expirationDate <= $currentDate) {
            throw new CertificateExpiredException(date('Y-m-d H:i:s', $expirationDate));
        }

        $daysUntilExpiration = ceil(($expirationDate - $currentDate) / (24 * 60 * 60));

        return [
            'expiring_soon' => $daysUntilExpiration <= $days,
            'days_until_expiration' => $daysUntilExpiration
        ];
    }
}

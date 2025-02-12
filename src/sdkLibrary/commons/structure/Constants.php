<?php

namespace Inter\Sdk\sdkLibrary\commons\structure;

/**
 * Class Constants
 *
 * This class contains all the constant values used throughout the SDK.
 */
class Constants
{
    public const DOC_CERTIFICATE = "https://developers.bancointer.com.br/v4/docs/onde-obter-o-certificado";
    public const URL_BANKING = "/banking/v2";
    public const URL_BANKING_BALANCE = self::URL_BANKING . "/saldo";
    public const URL_TOKEN = "/oauth/v2/token";
    public const URL_BANKING_STATEMENT = self::URL_BANKING . "/extrato";
    public const URL_BANKING_ENRICHED_STATEMENT = self::URL_BANKING_STATEMENT . "/completo";
    public const URL_BANKING_STATEMENT_PDF = self::URL_BANKING_STATEMENT . "/exportar";
    public const URL_BANKING_PAYMENT = self::URL_BANKING . "/pagamento";
    public const URL_BANKING_PAYMENT_DARF = self::URL_BANKING_PAYMENT . "/darf";
    public const URL_BANKING_PAYMENT_BATCH = self::URL_BANKING_PAYMENT . "/lote";
    public const URL_BANKING_PAYMENT_PIX = self::URL_BANKING . "/pix";
    public const URL_BANKING_WEBHOOK = self::URL_BANKING . "/webhooks";
    public const URL_PIX = "/pix/v2";
    public const URL_PIX_PIX = self::URL_PIX . "/pix";
    public const URL_PIX_LOCATIONS = self::URL_PIX . "/loc";
    public const URL_PIX_IMMEDIATE_BILLINGS = self::URL_PIX . "/cob";
    public const URL_PIX_SCHEDULED_BILLINGS = self::URL_PIX . "/cobv";
    public const URL_PIX_SCHEDULED_BILLINGS_BATCH = self::URL_PIX . "/lotecobv";
    public const URL_PIX_WEBHOOK = self::URL_PIX . "/webhook";
    public const URL_PIX_WEBHOOK_CALLBACKS = self::URL_PIX_WEBHOOK . "/callbacks";
    public const URL_BILLING = "/cobranca/v3/cobrancas";
    public const URL_BILLING_SUMMARY = self::URL_BILLING . "/sumario";
    public const URL_BILLING_WEBHOOK = self::URL_BILLING . "/webhook";
    public const URL_BILLING_WEBHOOK_CALLBACKS = self::URL_BILLING_WEBHOOK . "/callbacks";
    public const BILLET_BILLING_READ_SCOPE = "boleto-cobranca.read";
    public const BILLET_BILLING_WRITE_SCOPE = "boleto-cobranca.write";
    public const READ_BALANCE_SCOPE = "extrato.read";
    public const BILLET_PAYMENT_READ_SCOPE = "pagamento-boleto.read";
    public const BILLET_PAYMENT_WRITE_SCOPE = "pagamento-boleto.write";
    public const DARF_PAYMENT_WRITE_SCOPE = "pagamento-darf.write";
    public const BATCH_PAYMENT_READ_SCOPE = "pagamento-lote.read";
    public const BATCH_PAYMENT_WRITE_SCOPE = "pagamento-lote.write";
    public const PIX_PAYMENT_WRITE_SCOPE = "pagamento-pix.write";
    public const PIX_PAYMENT_READ_SCOPE = "pagamento-pix.read";
    public const WEBHOOK_BANKING_READ_SCOPE = "webhook-banking.read";
    public const WEBHOOK_BANKING_WRITE_SCOPE = "webhook-banking.write";
    public const PIX_IMMEDIATE_BILLING_READ_SCOPE = "cob.read";
    public const PIX_IMMEDIATE_BILLING_WRITE_SCOPE = "cob.write";
    public const PIX_SCHEDULED_BILLING_READ_SCOPE = "cobv.read";
    public const PIX_SCHEDULED_BILLING_WRITE_SCOPE = "cobv.write";
    public const PIX_SCHEDULED_BILLING_BATCH_WRITE_SCOPE = "lotecobv.write";
    public const PIX_SCHEDULED_BILLING_BATCH_READ_SCOPE = "lotecobv.read";
    public const PIX_READ_SCOPE = "pix.read";
    public const PIX_WRITE_SCOPE = "pix.write";
    public const PIX_LOCATION_READ_SCOPE = "payloadlocation.read";
    public const PIX_LOCATION_WRITE_SCOPE = "payloadlocation.write";
    public const PIX_WEBHOOK_READ_SCOPE = "webhook.read";
    public const PIX_WEBHOOK_WRITE_SCOPE = "webhook.write";
    public const DAYS_TO_EXPIRE = 30;
    public const CERTIFICATE_EXCEPTION_MESSAGE = "Certificate error!";
    public const GENERIC_EXCEPTION_MESSAGE = "Error executing SDK!";
}

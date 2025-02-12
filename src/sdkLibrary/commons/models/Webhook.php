<?php

namespace Inter\Sdk\sdkLibrary\commons\models;

use JsonException;

/**
 * The Webhook class represents a webhook configuration,
 * including the webhook URL and creation date.
 */
class Webhook
{
    /**
     * The URL of the webhook to be invoked.
     */
    private ?string $webhookUrl = null;

    /**
     * The date when the webhook was created.
     */
    private ?string $creationDate = null;

    /**
     * Constructs a new Webhook.
     *
     * @param string|null $webhookUrl The URL of the webhook to be invoked.
     * @param string|null $creationDate The date when the webhook was created.
     */
    public function __construct(?string $webhookUrl = null, ?string $creationDate = null)
    {
        $this->webhookUrl = $webhookUrl;
        $this->creationDate = $creationDate;
    }

    /**
     * Get the URL of the webhook.
     *
     * @return string|null
     */
    public function getWebhookUrl(): ?string
    {
        return $this->webhookUrl;
    }

    /**
     * Set the URL of the webhook.
     *
     * @param string|null $webhookUrl
     */
    public function setWebhookUrl(?string $webhookUrl): void
    {
        $this->webhookUrl = $webhookUrl;
    }

    /**
     * Get the creation date of the webhook.
     *
     * @return string|null
     */
    public function getCreationDate(): ?string
    {
        return $this->creationDate;
    }

    /**
     * Set the creation date of the webhook.
     *
     * @param string|null $creationDate
     */
    public function setCreationDate(?string $creationDate): void
    {
        $this->creationDate = $creationDate;
    }

    /**
     * Create a new Webhook instance using a builder pattern.
     *
     * @return WebhookBuilder
     */
    public static function builder(): WebhookBuilder
    {
        return new WebhookBuilder();
    }

    /**
     * Convert the object to a JSON string.
     *
     * @return string
     * @throws JsonException
     */
    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);
    }

    /**
     * Convert the object to an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'webhookUrl' => $this->webhookUrl,
            'creationDate' => $this->creationDate,
        ];
    }

    /**
     * Create a Webhook instance from an associative array.
     *
     * @param array $data
     * @return Webhook
     */
    public static function fromJson(mixed $data): self
    {
        return new self(
            $data['webhookUrl'] ?? null,
            $data['creationDate'] ?? null
        );
    }
}

/**
 * Builder class for Webhook
 */
class WebhookBuilder
{
    private ?string $webhookUrl = null;
    private ?string $creationDate = null;

    /**
     * Set the URL of the webhook.
     *
     * @param string|null $webhookUrl
     * @return self
     */
    public function webhookUrl(?string $webhookUrl): self
    {
        $this->webhookUrl = $webhookUrl;
        return $this;
    }

    /**
     * Set the creation date of the webhook.
     *
     * @param string|null $creationDate
     * @return self
     */
    public function creationDate(?string $creationDate): self
    {
        $this->creationDate = $creationDate;
        return $this;
    }

    /**
     * Build the Webhook instance.
     *
     * @return Webhook
     */
    public function build(): Webhook
    {
        return new Webhook($this->webhookUrl, $this->creationDate);
    }
}

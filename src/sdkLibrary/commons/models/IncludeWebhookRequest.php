<?php

namespace Inter\Sdk\sdkLibrary\commons\models;

use JsonException;

/**
 * The IncludeWebhookRequest class represents a request to
 * include a webhook URL for receiving notifications about specific events.
 */
class IncludeWebhookRequest
{
    /**
     * The URL of the webhook to be included.
     */
    private ?string $webhookUrl = null;

    /**
     * Constructs a new IncludeWebhookRequest.
     *
     * @param string|null $webhookUrl The URL of the webhook to be included.
     */
    public function __construct(?string $webhookUrl = null)
    {
        $this->webhookUrl = $webhookUrl;
    }

    /**
     * Get the webhook URL.
     *
     * @return string|null The webhook URL.
     */
    public function getWebhookUrl(): ?string
    {
        return $this->webhookUrl;
    }

    /**
     * Set the webhook URL.
     *
     * @param string|null $webhookUrl The webhook URL to set.
     */
    public function setWebhookUrl(?string $webhookUrl): void
    {
        $this->webhookUrl = $webhookUrl;
    }

    /**
     * Create a new IncludeWebhookRequest instance using a builder pattern.
     *
     * @return IncludeWebhookRequestBuilder
     */
    public static function builder(): IncludeWebhookRequestBuilder
    {
        return new IncludeWebhookRequestBuilder();
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
        ];
    }

    /**
     * Create an IncludeWebhookRequest instance from an associative array.
     *
     * @param array $data
     * @return IncludeWebhookRequest
     */
    public static function fromJson(array $data): self
    {
        return new self(
            $data['webhookUrl'] ?? null
        );
    }
}

/**
 * Builder class for IncludeWebhookRequest
 */
class IncludeWebhookRequestBuilder
{
    private ?string $webhookUrl = null;

    /**
     * Set the webhook URL.
     *
     * @param string|null $webhookUrl The webhook URL to set.
     * @return self
     */
    public function webhookUrl(?string $webhookUrl): self
    {
        $this->webhookUrl = $webhookUrl;
        return $this;
    }

    /**
     * Build the IncludeWebhookRequest instance.
     *
     * @return IncludeWebhookRequest
     */
    public function build(): IncludeWebhookRequest
    {
        return new IncludeWebhookRequest($this->webhookUrl);
    }
}

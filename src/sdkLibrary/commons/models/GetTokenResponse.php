<?php

namespace Inter\Sdk\sdkLibrary\commons\models;

use JsonSerializable;

/**
 * The GetTokenResponse class represents the response
 * object returned when obtaining an access token from the system.
 */
class GetTokenResponse implements JsonSerializable
{
    /**
     * The access token for authentication.
     */
    private ?string $accessToken = null;

    /**
     * The type of the token returned.
     */
    private ?string $tokenType = null;

    /**
     * The lifetime of the access token in seconds.
     */
    private ?int $expiresIn = null;

    /**
     * The scope of access granted by the token.
     */
    private ?string $scope = null;

    /**
     * The timestamp when the token was created.
     */
    private ?int $createdAt = null;

    public function __construct(
        ?string $accessToken = null,
        ?string $tokenType = null,
        ?int $expiresIn = null,
        ?string $scope = null,
        ?int $createdAt = null
    ) {
        $this->accessToken = $accessToken;
        $this->tokenType = $tokenType;
        $this->expiresIn = $expiresIn;
        $this->scope = $scope;
        $this->createdAt = $createdAt;
    }

    // Getters
    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    public function getTokenType(): ?string
    {
        return $this->tokenType;
    }

    public function getExpiresIn(): ?int
    {
        return $this->expiresIn;
    }

    public function getScope(): ?string
    {
        return $this->scope;
    }

    public function getCreatedAt(): ?int
    {
        return $this->createdAt;
    }

    // Setters
    public function setAccessToken(?string $accessToken): void
    {
        $this->accessToken = $accessToken;
    }

    public function setTokenType(?string $tokenType): void
    {
        $this->tokenType = $tokenType;
    }

    public function setExpiresIn(?int $expiresIn): void
    {
        $this->expiresIn = $expiresIn;
    }

    public function setScope(?string $scope): void
    {
        $this->scope = $scope;
    }

    public function setCreatedAt(?int $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Specify data which should be serialized to JSON
     */
    public function jsonSerialize(): array
    {
        return [
            'access_token' => $this->accessToken,
            'token_type' => $this->tokenType,
            'expires_in' => $this->expiresIn,
            'scope' => $this->scope,
            'createdAt' => $this->createdAt,
        ];
    }

    /**
     * Create a new GetTokenResponse instance using a builder pattern.
     */
    public static function builder(): GetTokenResponseBuilder
    {
        return new GetTokenResponseBuilder();
    }
}

/**
 * Builder class for GetTokenResponse
 */
class GetTokenResponseBuilder
{
    private ?string $accessToken = null;
    private ?string $tokenType = null;
    private ?int $expiresIn = null;
    private ?string $scope = null;
    private ?int $createdAt = null;

    public function accessToken(?string $accessToken): self
    {
        $this->accessToken = $accessToken;
        return $this;
    }

    public function tokenType(?string $tokenType): self
    {
        $this->tokenType = $tokenType;
        return $this;
    }

    public function expiresIn(?int $expiresIn): self
    {
        $this->expiresIn = $expiresIn;
        return $this;
    }

    public function scope(?string $scope): self
    {
        $this->scope = $scope;
        return $this;
    }

    public function createdAt(?int $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function build(): GetTokenResponse
    {
        return new GetTokenResponse(
            $this->accessToken,
            $this->tokenType,
            $this->expiresIn,
            $this->scope,
            $this->createdAt
        );
    }
}

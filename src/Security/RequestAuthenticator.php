<?php

namespace Czechgroup\EshopApiExposer\Security;

use Nette\Http\Request;
use Nette\Security\AuthenticationException;

final class RequestAuthenticator
{
    public function __construct(
        private string $apiKey,
        private string $apiSecret,
    ) {}

    public function authenticate(Request $request): void
    {
        $key = $request->getHeader('X-Api-Key');
        $signature = $request->getHeader('X-Signature');
        $timestamp = $request->getHeader('X-Timestamp');

        if (!$key || !$signature || !$timestamp) {
            throw new AuthenticationException('Missing auth headers');
        }

        if ($key !== $this->apiKey) {
            throw new AuthenticationException('Invalid API key');
        }

        $expected = hash_hmac('sha256', $timestamp, $this->apiSecret);

        if (!hash_equals($expected, $signature)) {
            throw new AuthenticationException('Invalid signature');
        }
    }
}

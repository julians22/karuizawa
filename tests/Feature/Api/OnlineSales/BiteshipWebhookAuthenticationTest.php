<?php

namespace Tests\Feature\Api\OnlineSales;

use Illuminate\Foundation\Testing\TestCase;
use Tests\CreatesApplication;

class BiteshipWebhookAuthenticationTest extends TestCase
{
    use CreatesApplication;

    public function test_valid_signature_header_is_accepted(): void
    {
        config([
            'biteship.webhook_signature_key' => 'X-Biteship-Signature',
            'biteship.webhook_signature_secret' => 'test-secret',
            'biteship.webhook_token' => null,
        ]);

        $this->withHeaders(['X-Biteship-Signature' => 'test-secret'])
            ->postJson('/api/v1/online-sales/webhooks/biteship')
            ->assertUnprocessable();
    }

    public function test_invalid_signature_header_is_rejected(): void
    {
        config([
            'biteship.webhook_signature_key' => 'X-Biteship-Signature',
            'biteship.webhook_signature_secret' => 'test-secret',
            'biteship.webhook_token' => null,
        ]);

        $this->withHeaders(['X-Biteship-Signature' => 'wrong-secret'])
            ->postJson('/api/v1/online-sales/webhooks/biteship')
            ->assertUnauthorized();
    }

    public function test_legacy_token_is_rejected_when_signature_is_configured(): void
    {
        config([
            'biteship.webhook_signature_key' => 'X-Biteship-Signature',
            'biteship.webhook_signature_secret' => 'test-secret',
            'biteship.webhook_token' => 'legacy-token',
        ]);

        $this->withHeaders(['Authorization' => 'legacy-token'])
            ->postJson('/api/v1/online-sales/webhooks/biteship')
            ->assertUnauthorized();
    }

    public function test_legacy_token_is_used_when_signature_is_not_configured(): void
    {
        config([
            'biteship.webhook_signature_key' => null,
            'biteship.webhook_signature_secret' => null,
            'biteship.webhook_token' => 'legacy-token',
        ]);

        $this->withHeaders(['Authorization' => 'legacy-token'])
            ->postJson('/api/v1/online-sales/webhooks/biteship')
            ->assertUnprocessable();
    }

    public function test_webhook_is_rejected_when_authentication_is_not_configured(): void
    {
        config([
            'biteship.webhook_signature_key' => null,
            'biteship.webhook_signature_secret' => null,
            'biteship.webhook_token' => null,
        ]);

        $this->postJson('/api/v1/online-sales/webhooks/biteship')
            ->assertUnauthorized();
    }

    public function test_partial_signature_configuration_is_rejected(): void
    {
        config([
            'biteship.webhook_signature_key' => 'X-Biteship-Signature',
            'biteship.webhook_signature_secret' => null,
            'biteship.webhook_token' => 'legacy-token',
        ]);

        $this->withHeaders([
            'X-Biteship-Signature' => 'test-secret',
            'Authorization' => 'legacy-token',
        ])->postJson('/api/v1/online-sales/webhooks/biteship')
            ->assertUnauthorized();
    }
}

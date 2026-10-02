<?php

namespace App\Services\Shipping;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Thin client around the Biteship REST API.
 * @see https://biteship.com/docs
 */
class BiteshipService
{
    protected string $baseUrl;
    protected ?string $apiKey;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('biteship.base_url'), '/');
        $this->apiKey = config('biteship.api_key');
    }

    protected function client()
    {
        return Http::withHeaders([
            'Authorization' => $this->apiKey,
            'Content-Type' => 'application/json',
        ])->baseUrl($this->baseUrl);
    }

    public function searchAreas(string $keyword, int $limit = 10): array
    {
        $response = $this->client()->get('/v1/maps/areas', [
            'countries' => 'ID',
            'input' => $keyword,
            'type' => 'single',
        ]);

        return $response->json() ?? [];
    }

    /**
     * Get courier rates for a set of items to a destination.
     *
     * @param array $destination ['area_id' => ..., 'latitude' => ..., 'longitude' => ..., 'postal_code' => ...]
     * @param array $items [['name' => ..., 'value' => price, 'quantity' => ..., 'weight' => grams], ...]
     */
    public function getRates(array $destination, array $items, ?array $couriers = null): array
    {
        $payload = [
            'origin_area_id' => config('biteship.origin.area_id'),
            'origin_latitude' => config('biteship.origin.latitude'),
            'origin_longitude' => config('biteship.origin.longitude'),
            'origin_postal_code' => config('biteship.origin.postal_code'),
            'destination_area_id' => $destination['area_id'] ?? null,
            'destination_latitude' => $destination['latitude'] ?? null,
            'destination_longitude' => $destination['longitude'] ?? null,
            'destination_postal_code' => $destination['postal_code'] ?? null,
            'couriers' => implode(',', $couriers ?? config('biteship.default_couriers')),
            'items' => $items,
        ];

        $response = $this->client()->post('/v1/rates/couriers', $payload);

        if (!$response->successful()) {
            Log::warning('Biteship getRates failed', ['payload' => $payload, 'response' => $response->json()]);
        }

        return $response->json() ?? [];
    }

    /**
     * Create a shipment order on Biteship.
     * Returns both the raw payload sent and the API response so callers can persist both.
     */
    public function createOrder(array $payload): array
    {
        $response = $this->client()->post('/v1/orders', $payload);

        if (!$response->successful()) {
            Log::error('Biteship createOrder failed', ['payload' => $payload, 'response' => $response->json()]);
        }

        return [
            'success' => $response->successful(),
            'status' => $response->status(),
            'request' => $payload,
            'response' => $response->json() ?? [],
        ];
    }

    public function trackOrder(string $waybillId, string $courierCode): array
    {
        $response = $this->client()->get("/v1/trackings/{$waybillId}/couriers/{$courierCode}");

        return $response->json() ?? [];
    }

    public function cancelOrder(string $biteshipOrderId, string $reason = 'Cancelled by admin'): array
    {
        $response = $this->client()->post("/v1/orders/{$biteshipOrderId}", [
            'status' => 'cancelled',
            'cancellation_reason' => $reason,
        ]);

        return $response->json() ?? [];
    }
}

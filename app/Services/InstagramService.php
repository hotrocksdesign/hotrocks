<?php

namespace App\Services;

use Illuminate\Http\Client\Client;
use Illuminate\Support\Facades\Http;
use App\Models\InstagramSettings;

class InstagramService
{
    private $apiToken;
    private $businessAccountId;

    public function __construct()
    {
        $settings = InstagramSettings::getActive();
        if ($settings) {
            $this->apiToken = $settings->graph_api_token;
            $this->businessAccountId = $settings->business_account_id;
        }
    }

    /**
     * Obtener los últimos posts de Instagram
     */
    public function getLatestPosts(int $limit = 9)
    {
        if (!$this->apiToken || !$this->businessAccountId) {
            return [];
        }

        try {
            $response = Http::get('https://graph.instagram.com/v18.0/' . $this->businessAccountId . '/media', [
                'fields' => 'id,caption,media_type,media_url,permalink,timestamp',
                'access_token' => $this->apiToken,
                'limit' => $limit,
            ]);

            return $response->json()['data'] ?? [];
        } catch (\Exception $e) {
            \Log::error('Instagram API Error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Refrescar token de acceso
     */
    public function refreshAccessToken()
    {
        if (!$this->apiToken) {
            return false;
        }

        try {
            $response = Http::post('https://graph.instagram.com/refresh_access_token', [
                'grant_type' => 'ig_refresh_token',
                'access_token' => $this->apiToken,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $settings = InstagramSettings::first();
                if ($settings) {
                    $settings->update([
                        'graph_api_token' => $data['access_token'],
                        'last_sync' => now(),
                    ]);
                }
                return true;
            }
        } catch (\Exception $e) {
            \Log::error('Instagram Token Refresh Error: ' . $e->getMessage());
            return false;
        }

        return false;
    }
}

<?php

namespace App\Clients;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use App\DTOs\ArtworkDTO;
use Exception;

class MetMuseumClientProvider
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = Config::get('services.met_museum_api_base_url', 'https://collectionapi.metmuseum.org/public/collection/v1');
    }

    public function fetchDepartments(): array
    {
        $response = Http::get("{$this->baseUrl}/departments");

        if ($response->failed()) {
            Log::error("Failed to fetch departments: {$response->status()} - " . $response->body());
            throw new Exception('Failed to fetch departments');
        }

        return $response->json()['departments'] ?? [];
    }

    public function fetchSearchResults(int $departmentId, string $searchTerm): array
    {
        $response = Http::get("{$this->baseUrl}/search", [
            'q' => $searchTerm,
            'departmentId' => $departmentId
        ]);

        if ($response->failed()) {
            Log::error("Search API Error: {$response->status()} - " . $response->body());
            throw new Exception('Failed to fetch search results');
        }

        $objectIds = collect($response->json()['objectIDs'] ?? [])->take(10);

        return $objectIds
            ->map(fn($id) => $this->fetchObject($id))
            ->filter()
            ->values()
            ->all();
    }

    public function fetchObject(int $id): ?ArtworkDTO
    {
        $response = Http::get("{$this->baseUrl}/objects/{$id}");

        if ($response->failed()) {
            Log::error("Failed to fetch artwork with ID: {$id} - {$response->status()} - " . $response->body());
            return null;
        }

        return new ArtworkDTO($response->json());
    }
}

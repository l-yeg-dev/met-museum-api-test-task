<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\DTOs\ArtworkDTO;
use Exception;
use Illuminate\Support\Facades\Log;

class MetMuseumService
{
    private $baseUrl = 'https://collectionapi.metmuseum.org/public/collection/v1';

    public function getDepartments()
    {
        return Cache::remember('departments', 3600, function () {
            $response = Http::get("{$this->baseUrl}/departments");

            if ($response->failed()) {
                throw new Exception('Failed to fetch departments');
            }

            return $response->json()['departments'];
        });
    }

    public function searchArtworks($departmentId, $searchTerm)
    {
        $response = Http::get("{$this->baseUrl}/search", [
            'q' => $searchTerm,
            'departmentId' => $departmentId
        ]);

        if ($response->failed()) {
            throw new Exception('Failed to fetch search results');
        }

        $objectIds = collect($response->json('objectIDs', []))->take(10);
        return $objectIds
            ->map(fn($id) => $this->getObject($id))
            ->filter(fn($item) => !!$item)
            ->all();
    }

    public function getObject($id)
    {
        return Cache::remember("artwork_{$id}", 3600, function () use ($id) {
            $response = Http::get("{$this->baseUrl}/objects/{$id}");

            if ($response->failed()) {
                Log::error("Failed to fetch artwork with ID: {$id}");
                return 0;
            }

            return new ArtworkDTO($response->json());
        });
    }
}

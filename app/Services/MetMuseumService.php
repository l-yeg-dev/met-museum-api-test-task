<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\DTOs\ArtworkDTO;
use Exception;
use App\Clients\MetMuseumClientProvider;

class MetMuseumService
{
    private MetMuseumClientProvider $client;
    private int $cacheTtl;

    public function __construct(MetMuseumClientProvider $client)
    {
        $this->client = $client;
        $this->cacheTtl = Config::get('services.cache_ttl', 3600);
    }

    public function getDepartments(): array
    {
        return Cache::remember('departments', $this->cacheTtl, function () {
            return $this->client->fetchDepartments();
        });
    }

    public function searchArtworks(int $departmentId, string $searchTerm): array
    {
        return Cache::remember("search_{$departmentId}_{$searchTerm}", $this->cacheTtl, function () use ($departmentId, $searchTerm) {
            return $this->client->fetchSearchResults($departmentId, $searchTerm);
        });
    }

    public function getObject(int $id): ?ArtworkDTO
    {
        return Cache::remember("artwork_{$id}", $this->cacheTtl, function () use ($id) {
            return $this->client->fetchObject($id);
        });
    }
}

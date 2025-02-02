<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArtworkSearchRequest;
use App\Services\MetMuseumService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ArtController extends Controller
{
    protected MetMuseumService $metMuseumService;

    public function __construct(MetMuseumService $metMuseumService)
    {
        $this->metMuseumService = $metMuseumService;
    }

    public function getDepartments()
    {
        try {
            return response()->json(['departments' => $this->metMuseumService->getDepartments()], 200);
        } catch (HttpException $e) {
            Log::error('Failed to fetch departments: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load departments'], $e->getStatusCode());
        } catch (\Exception $e) {
            Log::error('Unexpected error: ' . $e->getMessage());
            return response()->json(['error' => 'An unexpected error occurred'], 500);
        }
    }

    public function searchArtworks(ArtworkSearchRequest $request)
    {
        try {
            $result = $this->metMuseumService->searchArtworks($request->departmentId, $request->searchTerm);
            return response()->json($result, 200);
        } catch (HttpException $e) {
            Log::error('Search failed: ' . $e->getMessage());
            return response()->json(['error' => 'Search failed'], $e->getStatusCode());
        } catch (\Exception $e) {
            Log::error('Unexpected error: ' . $e->getMessage());
            return response()->json(['error' => 'An unexpected error occurred'], 500);
        }
    }

    public function getObject($id)
    {
        try {
            return response()->json($this->metMuseumService->getObject($id)->toArray(), 200);
        } catch (HttpException $e) {
            Log::error("Failed to fetch object ID {$id}: " . $e->getMessage());
            return response()->json(['error' => 'Failed to load artwork'], $e->getStatusCode());
        } catch (\Exception $e) {
            Log::error("Unexpected error while fetching object ID {$id}: " . $e->getMessage());
            return response()->json(['error' => 'An unexpected error occurred'], 500);
        }
    }
}

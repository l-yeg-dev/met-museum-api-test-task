<?php

namespace App\DTOs;

class ArtworkDTO
{
    public string $title;
    public ?string $image;
    public string $artist;
    public string $date;

    public function __construct(array $data)
    {
        $this->title = $data['title'] ?? 'Unknown Title';
        $this->image = $data['primaryImage'] ?? null;
        $this->artist = $data['artistDisplayName'] ?? 'Unknown Artist';
        $this->date = $data['objectDate'] ?? 'Unknown Date';
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'image' => $this->image,
            'artist' => $this->artist,
            'date' => $this->date,
        ];
    }
}

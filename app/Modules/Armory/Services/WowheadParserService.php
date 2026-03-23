<?php

namespace App\Services\Parser;

use Illuminate\Support\Facades\Http;

class WowheadParserService
{

    /**
     * URL WowHead XML
     */
    protected $url = 'https://www.wowhead.com/%s=%s&xml';

    /**
     * Parser a wowhead XML from a given URL and return item as JSON
     * 
     * @param string $url
     * @return array
     */
    public function parse(string $type = 'item', string $itemId): array
    {
        $response = Http::get(sprintf($this->url, $type, $itemId));

        if (!$response->ok()) {
            return [];
        }

        $xml = simplexml_load_string($response->body());

        if (!$xml || !isset($xml->item)) {
            return [];
        }

        $item = $xml->item;

        $data = [
            'id' => (int) $item['id'],
            'name' => trim((string) $item->name),
            'level' => (int) $item->level,
            'quality' => [
                'id' => (int) $item->quality['id'],
                'name' => (string) $item->quality,
            ],
            'class' => [
                'id' => (int) $item->class['id'],
                'name' => trim((string) $item->class),
            ],
            'subclass' => [
                'id' => (int) $item->subclass['id'],
                'name' => trim((string) $item->subclass),
            ],
            'icon' => [
                'displayId' => (int) $item->icon['displayId'],
                'name' => (string) $item->icon,
            ],
            'inventorySlot' => [
                'id' => (int) $item->inventorySlot['id'],
                'name' => (string) $item->inventorySlot,
            ],
            'link' => (string) $item->link,
        ];

        return $data;
    }
}
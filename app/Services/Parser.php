<?php

declare(strict_types=1);

namespace App\Services;

use DiDom\Document;

class Parser
{
    public static function parseBody(string $body): array
    {
        $elements = new Document($body);
        $h1 = optional($elements->first('h1'))->text();
        $keywords = optional($elements->first('meta[name=keywords]'))->getAttribute('content');
        $description = optional($elements->first('meta[name=description]'))->getAttribute('content');

        return [
            'h1' => $h1,
            'keywords' => $keywords,
            'description' => $description
        ];
    }

    public static function toLower(string $url): string
    {
        $parts = parse_url($url);
        $query = array_key_exists('query', $parts) ? "?{$parts['query']}" : '';

        return strtolower(implode([
            $parts['scheme'], '://', $parts['host'], $parts['path'] ?? ''
            ])) . $query;
    }
}
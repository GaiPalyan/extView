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
}
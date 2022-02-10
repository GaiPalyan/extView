<?php

declare(strict_types=1);

namespace App\Domain;

use App\Models\Url;
use Illuminate\Pagination\LengthAwarePaginator;

interface UrlRepositoryInterface
{
    public function getList(): array;
    public function getUrlCheckingData(Url $url): LengthAwarePaginator;
    public function save(string $url): Url;
    public function saveCheck(Url $url, array $parsedBody): void;
}

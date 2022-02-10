<?php

declare(strict_types=1);

namespace App\Domain;

use App\Models\Url;

class UrlManager
{
    private UrlRepositoryInterface $repository;

    public function __construct(UrlRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getUrlsList(): array
    {
        return $this->repository->getList();
    }

    public function getUrlRelatedData(Url $url): array
    {
        $checkData = $this->repository->getUrlCheckingData($url);
        return compact('url', 'checkData');
    }

    public function saveUrl(string $url): Url
    {
        return $this->repository->save($url);
    }

    public function saveUrlCheck(Url $url, array $parsedBody): void
    {
        $this->repository->saveCheck($url, $parsedBody);
    }
}

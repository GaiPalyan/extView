<?php

declare(strict_types=1);

namespace App\Domain;

use App\Models\Url;
use App\Models\UrlCheck;

class UrlManager
{
    private UrlRepositoryInterface $repository;

    public function __construct(UrlRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getUrlsList(string $searchBy = ''): array
    {
        return $this->repository->getList($searchBy);
    }

    public function getUrlRelatedData(Url $url): array
    {
        $checkList = $this->repository->getUrlCheckingData($url);
        return compact('url', 'checkList');
    }

    public function saveUrl(string $url): Url
    {
        return $this->repository->save($url);
    }

    public function saveUrlCheck(Url $url, array $parsedBody): void
    {
        $this->repository->saveCheck($url, $parsedBody);
    }

    public function getLustCheck(Url $url): UrlCheck
    {
        return $this->repository->getUrlLustCheck($url);
    }
}

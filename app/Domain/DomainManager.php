<?php

namespace App\Domain;

use App\Repository\DomainRepository;
use Carbon\Carbon;

class DomainManager
{
    private DomainRepository $repository;
    private Carbon $time;

    public function __construct(DomainRepository $repository)
    {
        $this->repository = $repository;
        $this->time = Carbon::now();
    }

    public function getDomainsList()
    {
        return $this->repository->getList();
    }

    public function getDomainPage(int $id)
    {
        return $this->repository->getPage($id);
    }

    public function prepareBasicDomainData(array $data)
    {
        $urlName = $data['url']['name'];
        $normalizeUrl = self::normalize($urlName);

        $domain = [
            'name' => $normalizeUrl,
            'created_at' => $this->time,
            'updated_at' => $this->time
        ];

        $this->repository->saveDomain($domain);
    }

    public function prepareDomainCkeckData(int $id)
    {
        $this->repository->getDomain($id);

        $domainCheck = [
            'url_id' => $id,
            'created_at' => $this->time,
            'updated_at' => $this->time
        ];

        $this->repository->saveDomainCheck($domainCheck);
    }

    private static function normalize(string $urlName): string
    {
        $parts = parse_url($urlName);
        return strtolower("{$parts['scheme']}://{$parts['host']}");
    }
}

<?php

declare(strict_types=1);

namespace App\Repository;

use App\Models\UrlChecks;
use App\Models\Urls;
use Exception;

class DBDomainRepository implements DBDomainRepositoryInterface
{
    public function getList(): array
    {
        $domains = Urls::query()
            ->select('id', 'name')
            ->orderByDesc('created_at')
            ->simplePaginate(10);
        $lastChecks = UrlChecks::query()
            ->select('url_id', 'status_code')
            ->selectRaw('max(updated_at) as last_check')
            ->groupBy('url_id', 'status_code')
            ->get()
            ->keyBy('url_id');
        return compact('domains', 'lastChecks');
    }

    public function getPage(int $id): array
    {
        if (!$this->isDomainExistById($id)) {
            abort(404);
        }

        $domain = $this->getDomainById($id);
        $domainChecks = UrlChecks::where('url_id', $id)
            ->orderByDesc('updated_at')
            ->paginate(10);

        return compact('domain', 'domainChecks');
    }

    public function getDomainById(int $id): Urls
    {
        return Urls::where('id', $id)
            ->firstOrNew();
    }

    public function getDomainByName(string $name): Urls
    {
        return Urls::where('name', $name)
            ->firstOrNew();
    }

    public function saveDomain(array $domain)
    {
        $domain = Urls::create($domain);
        try {
            $domain->save();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function saveDomainCheck(array $data)
    {
        $domainCheck = UrlChecks::create($data);
        try {
            $domainCheck->save();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function isDomainExist(string $identity): bool
    {
        return $this->isDomainExistById((int) $identity) || $this->isDomainExistByName($identity);
    }

    public function isDomainExistById(int $id): bool
    {
        return Urls::where('id', $id)->exists();
    }

    public function isDomainExistByName(string $name): bool
    {
        return Urls::where('name', $name)->exists();
    }
}

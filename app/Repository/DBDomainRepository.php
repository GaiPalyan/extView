<?php

declare(strict_types=1);

namespace App\Repository;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DBDomainRepository implements DBDomainRepositoryInterface
{
    public function getList(): View
    {
        $domains = DB::table('urls')
            ->select('id', 'name')
            ->orderByDesc('created_at')
            ->paginate(8);
        $lastChecks = DB::table('url_checks')
            ->select('url_id', 'status_code', DB::raw('max(updated_at) as last_check'))
            ->groupBy('url_id', 'status_code')
            ->get()
            ->keyBy('url_id');
        return view('domains.show', compact('domains', 'lastChecks'));
    }

    public function getPage(int $id): View
    {
        if (!$this->isDomainExistById($id)) {
            abort(404);
        }

        $domain = $this->getDomainById($id);
        $domainChecks = DB::table('url_checks')
            ->where('url_id', '=', $id)
            ->orderByDesc('updated_at')
            ->paginate(10);

        return view('domains.domain', compact('domain', 'domainChecks'));
    }

    public function getDomainById(int $id): mixed
    {
        return DB::table('urls')
            ->where('id', $id)
            ->first();
    }

    public function getDomainByName(string $name): mixed
    {
        return DB::table('urls')
            ->where('name', $name)
            ->first();
    }

    public function saveDomain(array $domain)
    {
        try {
            DB::table('urls')->insert($domain);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function updateDomainParam(int $id, string $column, int|string $value)
    {
        try {
            DB::table('urls')->where('id', $id)->update([$column => $value]);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function saveDomainCheck(array $data)
    {
        try {
            DB::table('url_checks')->insert($data);
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
        return DB::table('urls')->where('id', $id)->exists();
    }

    public function isDomainExistByName(string $name): bool
    {
        return DB::table('urls')->where('name', $name)->exists();
    }
}

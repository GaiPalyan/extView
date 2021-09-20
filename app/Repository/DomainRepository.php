<?php

declare(strict_types=1);

namespace App\Repository;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use stdClass;

class DomainRepository
{
    public function getList(): View
    {
        $domains = DB::table('urls')
            ->select('id', 'name')
            ->orderByDesc('created_at')
            ->simplePaginate(10);
        $lastChecks = DB::table('url_checks')
            ->select('url_id', 'status_code', DB::raw('max(updated_at) as last_check'))
            ->groupBy('url_id', 'status_code')
            ->get()
            ->keyBy('url_id');
        return view('domains.show', compact('domains', 'lastChecks'));
    }

    /**
     * @param int $id
     * @return View
     */
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

    /**
     * @param int $id
     * @return mixed
     */
    public function getDomainById(int $id): mixed
    {
        return DB::table('urls')
            ->where('id', $id)
            ->first();
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getDomainByName(string $name): mixed
    {
        return DB::table('urls')
            ->where('name', $name)
            ->first();
    }

    /**
     * @param array $domain
     * @return string|void
     */
    public function saveDomain(array $domain)
    {
        try {
            DB::table('urls')->insert($domain);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * @param int $id
     * @param string $column
     * @param int|string $value
     * @return string|void
     */
    public function updateDomainParam(int $id, string $column, int|string $value)
    {
        try {
            DB::table('urls')->where('id', $id)->update([$column => $value]);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * @param array $data
     * @return string|void
     */
    public function saveDomainCheck(array $data)
    {
        try {
            DB::table('url_checks')->insert($data);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * @param string $identity
     * @return bool
     */
    public function isDomainExist(string $identity): bool
    {
        return $this->isDomainExistById((int) $identity) || $this->isDomainExistByName($identity);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function isDomainExistById(int $id): bool
    {
        return DB::table('urls')->where('id', $id)->exists();
    }

    /**
     * @param string $name
     * @return bool
     */
    public function isDomainExistByName(string $name): bool
    {
        return DB::table('urls')->where('name', $name)->exists();
    }
}

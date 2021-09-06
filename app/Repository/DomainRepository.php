<?php

declare(strict_types=1);

namespace App\Repository;

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
        if (!$this->isDomainExist($id)) {
            abort(404);
        }

        $domain = $this->getDomain($id);
        $domainChecks = DB::table('url_checks')
            ->where('url_id', '=', $id)
            ->orderByDesc('updated_at')
            ->paginate(10);

        return view('domains.domain', compact('domain', 'domainChecks'));
    }

    /**
     * @param int|null $id
     * @param string|null $name
     * @return stdClass
     */
    public function getDomain(int $id = null, string $name = null): stdClass
    {
        return DB::table('urls')->where('id', $id)->orWhere('name', $name)->first();
    }

    /**
     * @param array $domain
     * @return string|void
     */
    public function saveDomain(array $domain)
    {
        try {
            DB::table('urls')->insert($domain);
        } catch (\Exception $e) {
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
        } catch (\Exception $e) {
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
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * @param int|null $id
     * @param string|null $name
     * @return bool
     */
    public function isDomainExist(int $id = null, string $name = null): bool
    {
        return DB::table('urls')->where('name', $name)->orWhere('id', $id)->exists();
    }
}

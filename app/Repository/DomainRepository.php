<?php

declare(strict_types=1);

namespace App\Repository;

use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

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

    public function getPage(int $id)
    {
        $domain = $this->getDomain($id);
        if (!$domain) {
            return abort(404);
        }

        $domainChecks = DB::table('url_checks')->where('url_id', '=', $id)->paginate(10);

        return view('domains.domain', compact('domain', 'domainChecks'));
    }

    /**
     * @param int|null $id
     * @param string|null $name
     * @return \Illuminate\Database\Query\Builder|mixed|null
     */
    public function getDomain(int $id = null, string $name = null)
    {
        return isset($id)
            ? DB::table('urls')->find($id)
            : DB::table('urls')->where('name', $name)->value('id');
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
     * @param $value
     * @return string|void
     */
    public function updateDomainParam(int $id, string $column, $value)
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
}

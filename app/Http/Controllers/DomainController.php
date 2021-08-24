<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DomainController extends Controller
{

    public function show()
    {
        $domains = DB::table('urls')
            ->distinct()
            ->get()
            ->sortBy('id');
        $lastChecks = DB::table('url_checks')
            ->select('url_id', 'status_code', DB::raw('max(updated_at) as last_check'))
            ->groupBy('url_id', 'status_code')
            ->get()
            ->keyBy('url_id');
        return view('domains.show', compact('domains', 'lastChecks'));
    }

    public function domainPage(int $id)
    {
        $domain = DB::table('urls')->find($id);
        if (!$domain) {
            return abort(404);
        }
        $domainChecks = DB::table('url_checks')->where('url_id', '=', $id)
        ->paginate(10);
        return view('domains.domain', compact('domain', 'domainChecks'));
    }

    public function store(Request $request)
    {
        $validateDomain = Validator::make($request->all(), ['url.name' => 'required|url|max:255|unique:urls,name']);
        if ($validateDomain->fails()) {
            flash($validateDomain->errors()->first('url.name'))->info()->important();
            return redirect()->route('domains.create');
        }

        $data = $request->toArray();
        $urlName = $data['url']['name'];
        $normalizeUrl = self::normalize($urlName);
        $currentTime = Carbon::now();

        $domain = [
            'name' => $normalizeUrl,
            'created_at' => $currentTime,
            'updated_at' => $currentTime
        ];

         DB::table('urls')->insert($domain);
         flash('Domain has been added!')->success()->important();
         return redirect()->route('domains.create');
    }

    public function create()
    {
        return view('domains.create');
    }

    /**
     * @param string $urlName
     * @return string
     */
    protected static function normalize(string $urlName): string
    {
        $parts = parse_url($urlName);
        return strtolower("{$parts['scheme']}://{$parts['host']}");
    }
}

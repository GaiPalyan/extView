<?php

namespace App\Http\Controllers;

use Faker\Factory;
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
        return view('domains.show', ['domains' => $domains]);
    }

    public function domainPage(int $id)
    {
        $domain = DB::table('urls')->find($id);
        return view('domains.domain', compact('domain'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), ['url.name' => 'required|url|max:255|unique:urls,name']);
        if ($validator->fails()) {
            flash('Domain not valid!')->error()->important();
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

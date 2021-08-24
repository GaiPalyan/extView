<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DomainChecksController extends Controller
{
    public function store($id)
    {
        $domain = DB::table('urls')->find($id);
        $created_at = Carbon::now();
        $updated_at = Carbon::now();

        $domainCheck = [
            'url_id' => $domain->id,
            'created_at' => $created_at,
            'updated_at' => $updated_at
        ];

        try {
            DB::table('url_checks')->insert($domainCheck);
            flash('Domain check successfully')->success()->important();
            return redirect()->route('domain.show', $domain->id);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}

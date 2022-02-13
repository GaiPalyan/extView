<?php

namespace App\Http\Controllers;

use App\Domain\UrlManager;
use App\Http\Requests\UrlRequest;
use App\Models\Url;
use App\Services\Parser;
use Exception;
use Illuminate\Support\Facades\Http;

class ApiUrlController extends Controller
{
    private UrlManager $manager;

    public function __construct(UrlManager $manager)
    {
        $this->manager = $manager;
    }

    public function index()
    {
        return $this->manager->getUrlsList();
    }

    public function show(Url $url)
    {
        $url = $this->manager->getUrlRelatedData($url);
        return response()->json($url);
    }

    public function store(UrlRequest $request)
    {
        $url = $request->input('name');

        try {
            Http::get($url)->throw();
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Incorrect address'
            ])->setStatusCode(422);
        }

        $url = $this->manager->saveUrl($url);

        return response()->json(['url' => $url]);
    }

    public function storeCheck(Url $url)
    {
        try {
            $response = Http::get($url->getAttribute('name'));
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Incorrect address'
            ])->setStatusCode(422);
        }

        $parsedBody = Parser::parseBody($response->body());
        $parsedBody['status_code'] = $response->status();
        $this->manager->saveUrlCheck($url, $parsedBody);

        return response()->json([
            'success' => 'Url has been checked'
        ]);
    }
}

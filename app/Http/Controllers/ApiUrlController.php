<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domain\UrlManager;
use App\Http\Requests\SearchRequest;
use App\Http\Requests\UrlRequest;
use App\Models\Url;
use App\Services\Parser;
use Exception;
use Illuminate\Http\Request;
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
        return $this->manager->getUrlsList()['list']
                             ->getCollection()
                             ->toJson();
    }

    public function search(SearchRequest $request)
    {
        return $this->manager->getUrlsList($request->query('field'))['list']->getCollection()->toJson();
    }

    public function show(Url $url)
    {
        return response()->json($this->manager->getUrlRelatedData($url));
    }

    public function store(UrlRequest $request)
    {
        $url = $request->input('name');


        try {
            Http::get($url)->throw();
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Address is not exist or action violates this site\'s security policy'
            ])->setStatusCode(422);
        }

        $url = $this->manager->saveUrl($url);

        return response()->json(['id' => $url->getAttribute('id'), 'success' => 'Url added']);
    }

    public function storeCheck(Url $url)
    {
        try {
            $response = Http::get($url->getAttribute('name'));
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Address is not exist or action violates this site\'s security policy'
            ])->setStatusCode(422);
        }

        $parsedBody = Parser::parseBody($response->body());
        $parsedBody['status_code'] = $response->status();
        $this->manager->saveUrlCheck($url, $parsedBody);
        $latest = $this->manager->getLustCheck($url);

        return response()->json([
            'latest' => $latest,
            'success' => 'Url has been checked',
        ]);
    }
}

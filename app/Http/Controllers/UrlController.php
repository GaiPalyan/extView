<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domain\UrlExceptions\FailedUrlSaveException;
use App\Domain\UrlManager;
use App\Http\Requests\UrlRequest;
use App\Models\Url;
use App\Services\Parser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class UrlController extends Controller
{
    private UrlManager $manager;

    /**
     * @param UrlManager $manager
     */
    public function __construct(UrlManager $manager)
    {
        $this->manager = $manager;
    }

    public function store(UrlRequest $request): RedirectResponse
    {
        $url = Parser::toLower($request->input('name'));

        try {
            Http::get($url)->throw();
        } catch (\Exception $e) {
            $message = flash('Некорректный адрес')->error()->important();
            return back()->withErrors($message->messages);
        }

        try {
            $url = $this->manager->saveUrl($url);
            return redirect()->route('url.show', $url);
        } catch (FailedUrlSaveException $exception) {
            flash($exception->getMessage())->error()->important();
            return back();
        }
    }

    /**
     * Show urls main list
     */
    public function index(): View
    {
        $list = $this->manager->getUrlsList();
        return view('urls.index', $list);
    }

    public function show(Url $url): View
    {
        $relatedData = $this->manager->getUrlRelatedData($url);
        return view('urls.show', $relatedData);
    }

    public function storeCheck(Url $url): RedirectResponse
    {
        try {
            $response = Http::get($url->getAttribute('name'));
        } catch (\Exception $e) {
            flash('Некорректный адрес')->error()->important();
            return back();
        }

        $parsedBody = Parser::parseBody($response->body());
        $parsedBody['status_code'] = $response->status();
        $this->manager->saveUrlCheck($url, $parsedBody);
        flash('Проверка прошла успешно')->success()->important();

        return back();
    }

    /**
     * @return View
     */
    public function create(): View
    {
        return view('urls.create');
    }
}

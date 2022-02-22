<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domain\UrlManager;
use App\Models\Url;
use App\Services\Parser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class UrlController extends Controller
{
    /**
     * Show urls index page
     */
    public function index(): View
    {
        return view('urls.index');
    }

    /**
     * Show url personal page
     */
    public function show(): View
    {
        return view('urls.show');
    }

    /**
     * Show url create form template
     */
    public function create(): View
    {
        return view('urls.create');
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domain\DomainManager;
use DiDom\Exceptions\InvalidSelectorException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class DomainController extends Controller
{
    private DomainManager $manager;

    /**
     * @param DomainManager $manager
     */
    public function __construct(DomainManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Validate and store domain
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $validateDomain = Validator::make(
            $request->input('url'),
            [
                'name' => ['required', 'url', 'max:255']
            ],
            $messages = [
                'required' => 'Поле ввода не может быть пустым',
                'url' => 'Некорректный адрес',
                'max' => 'Максимальная допустимая длина адреса 255 символов',
            ]
        );

        if ($validateDomain->fails()) {
            flash($validateDomain->errors()->first('name'))->error()->important();

            return back()->withInput()->withErrors($validateDomain);
        }

        $requestData = $request->toArray();
        $name = $requestData['url']['name'];
        $existDomain = $this->manager->getDomainInfo($name);

        if ($existDomain) {
            flash('Адрес уже существует')->info()->important();
            return  redirect()->route('domain_personal_page.show', $existDomain->id);
        }

        $this->manager->prepareBasicDomainData($name);
        flash('Адрес добавлен в базу!')->success()->important();

        $createdDomain = $this->manager->getDomainInfo($name);
        return redirect()->route('domain_personal_page.show', $createdDomain->id);
    }

    /**
     * Show domains main list
     */
    public function show(): View
    {
        return $this->manager->getDomainsList();
    }

    /**
     * Show domain personal page
     *
     * @param int $id
     * @return View
     */
    public function domainPage(int $id): View
    {
        return $this->manager->getDomainPersonalPage($id);
    }

    /**
     * Store domain parsing result
     *
     * @param int $id
     * @return RedirectResponse
     * @throws InvalidSelectorException
     */
    public function storeCheck(int $id): RedirectResponse
    {
        $this->manager->prepareDomainCheckData($id);
        return back();
    }

    /**
     * @return View
     */
    public function create(): View
    {
        return view('domains.create');
    }
}

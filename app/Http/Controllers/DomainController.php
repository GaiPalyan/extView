<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domain\DomainManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DomainController extends Controller
{
    private DomainManager $manager;

    public function __construct(DomainManager $manager)
    {
        $this->manager = $manager;
    }

    public function show()
    {
        return $this->manager->getDomainsList();
    }

    public function domainPage(int $id)
    {
        return $this->manager->getDomainPersonalPage($id);
    }

    public function store(Request $request)
    {
        $validateDomain = Validator::make(
            $request->input('url'),
            ['name' => 'required|url|max:255'],
            $messages = [
                'required' => 'Поле ввода не может быть пустым',
                'url' => 'Некорректный адрес',
                'max' => 'Максимальная допустимая длина адреса 255 символов',
            ]
        );

        if ($validateDomain->fails()) {
            flash($validateDomain->errors()->first('name'))->error()->important();
            return redirect()->route('domains.create');
        }

        $requestData = $request->toArray();
        $name = $requestData['url']['name'];
        $existDomain = $this->manager->getDomainInfo($name);

        if ($existDomain) {
            flash('Адрес уже существует')->info()->important();
            return  redirect()->route('domain.show', $existDomain);
        }

        $this->manager->prepareBasicDomainData($name);

        flash('Адрес добавлен в базу!')->success()->important();

        return redirect()->route('domains.create');
    }

    public function storeCheck(int $id)
    {
        $this->manager->prepareDomainCheckData($id);
        flash('Проверка прошла успешно')->success()->important();
        return redirect()->route('domain.show', $id);
    }

    public function create()
    {
        return view('domains.create');
    }
}

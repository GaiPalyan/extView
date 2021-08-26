<?php

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
        return $this->manager->getDomainPage($id);
    }

    public function store(Request $request)
    {
        $validateDomain = Validator::make(
            $request->all(),
            ['url.name' => 'required|url|max:255|unique:urls,name'],
            [
                'required' => 'Поле ввода не может быть пустым',
                'url' => 'Некорректный адрес',
                'max' => 'Максимальная допустимая длина адреса 255 символов',
                'unique' => 'Такой адрес уже есть в базе'
            ]
        );

        if ($validateDomain->fails()) {
            flash($validateDomain->errors()->first('url.name'))->error()->important();
            return redirect()->route('domains.create');
        }

        $data = $request->toArray();
        $this->manager->prepareBasicDomainData($data);

        flash('Адрес добавлен в базу!')->success()->important();

        return redirect()->route('domains.create');
    }

    public function storeCheck(int $id)
    {
        $this->manager->prepareDomainCkeckData($id);
        flash('Проверка прошла успешно')->success()->important();
        return redirect()->route('domain.show', $id);
    }

    public function create()
    {
        return view('domains.create');
    }
}

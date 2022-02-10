@extends('layouts.app')
@section('content')
<div class="container-lg">
        <h1 class="mt-5 mb-3">Адрес: {{$url->name}}</h1>
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-nowrap">
                <tbody>
                <tr>
                    <td>{{__('ID')}}</td>
                    <td>{{$url->id}}</td>
                </tr>
                <tr>
                    <td>{{__('Имя')}}</td>
                    <td>{{$url->name}}</td>
                </tr>
                <tr>
                    <td>{{__('Дата создания')}}</td>
                    <td>{{$url->created_at}}</td>
                </tr>
                <tr>
                    <td>{{__('Дата создания')}}</td>
                    <td>{{$url->updated_at}}</td>
                </tr>
                </tbody>
            </table>
        </div>

    <div class="table-responsive">
        <h2 class="mt-5 mb-3">{{__('Проверки')}}</h2>
        <form class="mb-2" action="{{ route('url_checks.store', $url) }}" method="post">
            @csrf
            <input class="btn btn-primary" type="submit" value="Запустить проверку">
        </form>
        <table class="table table-bordered table-hover text-nowrap">
                <tr>
                    <th>{{__('ID')}}</th>
                    <th>{{__('Код ответа')}}</th>
                    <th>{{__('h1')}}</th>
                    <th>{{__('keywords')}}</th>
                    <th>{{__('description')}}</th>
                    <th>{{__('Дата создания')}}</th>
                </tr>
            @foreach($checkData as $check)
                <tr>
                    <td>{{$check->id}}</td>
                    <td>{{$check->status_code}}</td>
                    <td>{{Str::of($check->h1)->limit(20)}}</td>
                    <td>{{Str::of($check->keywords)->limit(20)}}</td>
                    <td>{{Str::of($check->description)->limit(20)}}</td>
                    <td>{{Str::of($check->created_at)->limit(20)}}</td>
                </tr>
            @endforeach
        </table>
    </div>
</div>
@endsection

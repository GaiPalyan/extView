@extends('layouts.app')
@section('content')
<div class="container-lg">
        <h1 id="h1_url_name" class="mt-5 mb-3">{{__('Адрес: ')}}</h1>
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-nowrap">
                <tbody>
                    <tr id="id">
                        <td>{{__('ID')}}</td>
                    </tr>
                    <tr id="table_url_name">
                        <td>{{__('Имя')}}</td>
                    </tr>
                    <tr id="created_at">
                        <td>{{__('Дата создания')}}</td>
                    </tr>
                    <tr id="updated_at">
                        <td>{{__('Дата обновления')}}</td>
                    </tr>
                </tbody>
            </table>
        </div>

    <div class="table-responsive">
        <h2 class="mt-5 mb-3">{{__('Проверки')}}</h2>
        <form id="check" class="mb-2" method="post">
            @csrf
            <input id="check" class="btn btn-primary" type="submit" value="Запустить проверку">
        </form>
        <div class="action-message" id="alert"></div>
        <table class="table table-bordered table-hover text-nowrap">
            <thead>
                <tr>
                    <th>{{__('ID')}}</th>
                    <th>{{__('Код ответа')}}</th>
                    <th>{{__('h1')}}</th>
                    <th>{{__('keywords')}}</th>
                    <th>{{__('description')}}</th>
                    <th>{{__('Дата создания')}}</th>
                </tr>
            </thead>
            <tbody id="check_results">
            </tbody>
        </table>
    </div>
</div>
<script id="entity" src="{{ secure_asset('js/urls/show.js') }}"></script>
@endsection

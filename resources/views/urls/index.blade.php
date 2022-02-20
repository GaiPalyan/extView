@extends('layouts.app')
@section('content')
<script src="{{ asset('js/urls/index.js') }}"></script>
<div class="container-lg">
    <h1 class="mt-5 mb-3">Сайты</h1>
        <div class="table-responsive">
            <div class="input-group mb-3">
                <form id="search" action="{{ route('api.search') }}" class="d-flex justify-content-center">
                    <input id="search_param" name="field" type="text" class="search form-control">
                    <input class="btn btn-outline-secondary" type="submit" value="{{__('Поиск')}}">
                </form>
            </div>
            <table id="urls_table" class="table table-bordered table-hover text-nowrap">
                <thead>
                    <tr class="thead-light">
                        <th>ID</th>
                        <th>Имя</th>
                        <th>Последняя проверка</th>
                        <th>Код ответа</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
</div>
@endsection

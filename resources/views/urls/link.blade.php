@extends('layouts.app')
@section('content')
    <div class="container-lg">
        <div class="mt-lg-5 col-lg-5 mx-auto">
            <div class="text-center">
                <div class="d-flex card">
                    <div class="card-body">
                        <p class="card-text">{{__('Адрес сохранен в базу.')}}</p>
                        <a href="{{ route('url.show', $url) }}" class="btn btn-primary">{{__('Проверить')}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@extends('layouts.app')
@section('content')
<div class="container-lg">
    <h1 class="mt-5 mb-3">Сайты</h1>
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-nowrap">
                <tbody>
                    <tr class="thead-light">
                        <th>ID</th>
                        <th>Имя</th>
                        <th>Последняя проверка</th>
                        <th>Код ответа</th>
                    </tr>
                    <tr>
                        @foreach($list as $url)
                        <td>
                            {{ $url->id }}
                        </td>
                        <td>
                            <a href="{{route('url.show', $url->id)}}">{{$url->name}}</a>
                        </td>
                        <td>
                            {{ $url->last_check }}
                        </td>
                        <td>
                            {{ $url->status_code }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    {{$list->links()}}
</div>
@endsection

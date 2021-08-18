@extends('layouts.app')
@section('content')
    <div class="container-lg">
        <h1 class="mt-5 mb-3">Сайт: {{$domain->name}}</h1>
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-nowrap">
                <tbody>
                <tr>
                    <td>ID</td>
                    <td>{{$domain->id}}</td>
                </tr>
                <tr>
                    <td>Имя</td>
                    <td>{{$domain->name}}</td>
                </tr>
                <tr>
                    <td>Дата создания</td>
                    <td>{{$domain->created_at}}</td>
                </tr>
                <tr>
                    <td>Дата обновления</td>
                    <td>{{$domain->updated_at}}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    {!! Form::open() !!}
    {!! Form::submit('Запустить проверку', $attributes = ['class' => 'btn btn-primary']) !!}
    {!! Form::close() !!}
    <div class="table-responsive">
        <table class="table table-bordered table-hover text-nowrap">
            <tbody>
                <tr>
                    <th>ID</th>
                    <th>Код ответа</th>
                    <th>h1</th>
                    <th>keywords</th>
                    <th>description</th>
                    <th>Дата создания</th>
                </tr>
            </tbody>
        </table>
    </div>
@endsection

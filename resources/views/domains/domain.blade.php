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
    {!! Form::open(['route' => ['domain.checks.store', $domain->id]]) !!}
    {!! Form::submit('Запустить проверку', $attributes = ['class' => 'btn btn-primary']) !!}
    {!! Form::close() !!}
    <div class="table-responsive">
        <table class="table table-bordered table-hover text-nowrap">

                <tr>
                    <th>ID</th>
                    <th>Код ответа</th>
                    <th>h1</th>
                    <th>keywords</th>
                    <th>description</th>
                    <th>Дата создания</th>
                </tr>
            @foreach($domainChecks as $domainCheckParams)
                <tr>
                    <td>{{$domainCheckParams->id}}</td>
                    <td>{{$domainCheckParams->status_code}}</td>
                    <td>{{$domainCheckParams->h1}}</td>
                    <td>{{$domainCheckParams->keywords}}</td>
                    <td>{{$domainCheckParams->description}}</td>
                    <td>{{$domainCheckParams->created_at}}</td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection

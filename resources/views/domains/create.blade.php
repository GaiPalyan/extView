@extends('layouts.app')
@section('content')
    @include('flash::message')
    <div class="jumbotron jumbotron-fluid bg-dark">
        <div class="container-lg">
            <div class="row">
                <div class="col-12 col-md-10 col-lg-8 mx-auto text-white">
                    <h1 class="display-3">Анализатор страниц</h1>
                    <p class="lead">Бесплатно проверяйте сайты на SEO пригодность</p>
                    {!! Form::open([
                        'route' => 'domains.create',
                        'class' => 'd-flex justify-content-center'
                    ]) !!}
                    {!! Form::text('url[name]', $value = null, $attributes = [
                                    'class' => 'form-control form-control-lg',
                                    'placeholder' => 'https://www.example.com'
                                    ]) !!}
                    {!! Form::submit('Send', $attributes = [
                                    'class' => 'btn btn-lg btn-primary ml-3 px-5 text-uppercase'
                                    ]) !!}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
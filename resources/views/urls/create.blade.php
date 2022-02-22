@extends('layouts.app')
@section('content')
    <div class="jumbotron jumbotron-fluid bg-dark">
        <div class="container-lg">
            <div class="row">
                <div class="col-12 col-md-10 col-lg-8 mx-auto text-white" id="test">
                    <h1 class="display-3 main-title">{{__('Анализатор страниц')}}</h1>
                    <p class="lead">{{__('Бесплатно проверяйте сайты на SEO пригодность')}}</p>
                    <form id="url_store" class="d-flex justify-content-center" action="{{ route('api.store') }}" method="post">
                        @csrf
                        <input class="form-control form-control-lg @error('name') is-invalid @enderror"
                               id="my_url"
                               type="text"
                               name="name"
                               value=""
                               placeholder="https://www.example.com">

                        <input class="btn btn-lg btn-primary ml-3 px-5 text-uppercase"
                               type="submit"
                               value="Отправить">
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="action-message" id="alert"></div>
@endsection

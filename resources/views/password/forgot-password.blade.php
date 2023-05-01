@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endpush

@section('content')
    <div class="container">
        <div class="row justify-content-center align-items-center" style="min-height: calc(100vh - 100px);">
            <div class="col-md-6 d-flex justify-content-center">
                <div class="card">
                    <div class="card-header text-white">
                        <h3 class="text-center">Esqueci minha senha</h3>
                    </div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf

                            <div class="form-group row mt-5">
                                <label for="email" class="col-md-4 col-form-label text-md-right text-white">Insira o email</label>

                                <div class="col-md-8">
                                    <input id="email" type="email"
                                           class="form-control @error('email') is-invalid @enderror" name="email"
                                           value="{{ old('email') }}" autocomplete="email" autofocus>

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mt-5">
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn btn-primary">
                                        Enviar link de recuperação de senha
                                    </button>
                                </div>
                            </div>

                            <div class="card-footer mt-5">
                                <div class="d-flex justify-content-center mb-2 text-white">
                                    Lembrou da senha?<a href="{{ route('login.page') }}" class="ms-2">Faça login</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endpush

@section('content')
    <div class="container">
        <div class="row justify-content-center align-items-center" style="min-height: calc(100vh - 100px);">
            <div class="col-md-6 d-flex justify-content-center">
                <div class="card">
                    <div class="card-header text-white text-center">
                        <h3>{{ __('Redefinir Senha') }}</h3>
                    </div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf

                            <div class="form-group row mt-2">
                                <label for="token"
                                       class="col-md-4 col-form-label text-md-right text-white">{{ __('Token') }}</label>

                                <div class="col-md-6">
                                    <input id="token" type="text"
                                           class="form-control @error('token') is-invalid @enderror" name="token"
                                           value="{{ $token }}" required autofocus readonly>


                                    @error('token')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mt-2">
                                <label for="password"
                                       class="col-md-4 col-form-label text-md-right text-white">{{ __('Senha') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password"
                                           class="form-control @error('password') is-invalid @enderror" name="password"
                                    >

                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mt-2">
                                <label for="password-confirm"
                                       class="col-md-4 col-form-label text-md-right text-white">{{ __('Confirme a senha') }}</label>

                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control"
                                           name="password_confirmation">
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Redefinir Senha') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

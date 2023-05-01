@extends('layouts.app')

@section('Login', 'Laravel')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endpush

@section('content')
    <div class="container">
        <div class="row justify-content-center align-items-center" style="min-height: calc(100vh - 100px);">
            <div class="col-md-6 d-flex justify-content-center">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">Login</h3>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success text-center">
                                {{ session('success') }}
                            </div>
                        @endif
                        <form method="post" action="{{route('auth.user')}}">
                            @csrf
                            <div class="mb-3">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control @error('email') is-invalid @enderror"
                                           name="email" placeholder="Email" value="{{ old('email') }}"
                                           autocomplete="email">
                                </div>
                                @error('email')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                           name="password" placeholder="Senha">
                                </div>
                                @error('password')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="d-grid">
                                <input type="submit" value="Login" class="btn btn-primary">
                            </div>
                        </form>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-center mb-2 text-white">
                            Não tem uma conta?<a class="ms-2" href="{{route('register.page')}}">Registre-se</a>
                        </div>
                        <div class="d-flex justify-content-center">
                            <a href="{{ route('password.request') }}">Esqueceu sua senha?</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

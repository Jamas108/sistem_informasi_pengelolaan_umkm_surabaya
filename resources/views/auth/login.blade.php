@extends('layouts.app')

@section('content')
    <div class="login-container d-flex justify-content-center align-items-center"
        style="background-image: url('{{ Vite::asset('resources/images/bglogin.png') }}'); background-size: cover; background-position: center; height: 100vh; width: 100%;">

        <!-- Use a flexbox container for side-by-side layout -->
        <div class="d-flex w-100">
            <!-- Logo Section -->
            <div class="col-md-6 d-flex justify-content-center align-items-center p-0">
                <img src="{{ Vite::asset('../resources/images/logo_sbyhebat.png') }}" alt="Logo Surabaya Hebat"
                    class="img-fluid" />
            </div>

            <!-- Form Section -->
            <div class="col-md-5 d-flex justify-content-center align-items-center p-0">
                <div class="card w-75">
                    <div class="text-center">
                        <h4 style="color: black;" class="mt-3 mx-5">Sistem Informasi UMKM Kota Surabaya</h4>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <!-- Username Field -->
                            <div class="mb-3">
                                <input id="username" type="text"
                                    class="form-control @error('username') is-invalid @enderror" name="username"
                                    placeholder="Username" value="{{ old('username') }}" required autofocus>
                                @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- Password Field -->
                            <div class="mb-3">
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password" required
                                    placeholder="Password" autocomplete="current-password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- Buttons -->
                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-sign-in-alt"></i> {{ __('Masuk') }}
                                </button>
                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    {{ __('Lupa Password?') }}
                                </a>
                            </div>
                        </form>
                        <!-- Link Pendaftaran -->
                        <div class="text-center mt-4">
                            <p class="small text-gray-600">Belum punya akun?
                                <a href="{{ route('register') }}" class="text-primary font-weight-bold">Daftar sekarang</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        /* Full-page container with no space between logo and form */
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            width: 100%;
            background-size: cover;
            background-position: center;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-link {
            color: #007bff;
        }

        .form-check-label {
            font-size: 14px;
        }

        /* Remove padding/margins from columns to ensure they fill the space */
        .col-md-6 {
            padding: 0;
        }

        /* Ensure that the form has proper spacing and width */
        .card {
            width: 90%;
            max-width: 500px;
            /* Adjust the width of the card */
        }

        .img-fluid {
            max-width: 60%;
            /* Limit logo size */
            height: auto;
        }
    </style>
@endsection

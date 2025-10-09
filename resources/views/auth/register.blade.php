@extends('layouts.app')

@section('content')
    <div class="container py-1">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="card shadow rounded-4 border-0">
                    <div class="card-body p-5">
                        <h2 class="mb-3 text-center fw-bold text-graphite">{{ __('Register') }}</h2>

                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label">{{ __('Name') }}</label>
                                <input id="name" type="text" class="form-control form-control-lg @error('name') is-invalid @enderror"
                                       name="name" value="{{ old('name') }}" required autofocus>
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="surname" class="form-label">{{ __('Surname') }}</label>
                                <input id="surname" type="text" class="form-control form-control-lg @error('surname') is-invalid @enderror"
                                       name="surname" value="{{ old('surname') }}" required>
                                @error('surname')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">{{ __('Email Address') }}</label>
                                <input id="email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror"
                                       name="email" value="{{ old('email') }}" required>
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">{{ __('Phone number') }}</label>
                                <input id="phone" type="text" class="form-control form-control-lg @error('phone') is-invalid @enderror"
                                       name="phone" value="{{ old('phone') }}" required>
                                @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">{{ __('Password') }}</label>
                                <input id="password" type="password" class="form-control form-control-lg @error('password') is-invalid @enderror"
                                       name="password" required>
                                @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password-confirm" class="form-label">{{ __('Confirm Password') }}</label>
                                <input id="password-confirm" type="password" class="form-control form-control-lg"
                                       name="password_confirmation" required>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </form>

                        <div class="mt-4 text-center">
                            <small class="text-muted">
                                {{ __("Already have an account?") }} <a href="{{ route('login') }}" class="text-graphite">{{ __('Login') }}</a>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

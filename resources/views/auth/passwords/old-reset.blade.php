@extends('frontend.layouts.master')

@section('title', 'Hand Craft || Forgot Page')

@section('main-content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Reset Password') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.send') }}">
                            @csrf

                            <div class="form-group row">
                                <label for="email_or_phone"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Your Email or Whatsapp Number') }}</label>

                                <div class="col-md-6">
                                    <input id="email_or_phone" type="text"
                                        class="form-control @error('email_or_phone') is-invalid @enderror"
                                        name="email_or_phone" value="{{ old('email_or_phone') }}" required
                                        autocomplete="email" autofocus>
                                    @if (session('success'))
                                        <div class="alert alert-success">
                                            {{ session('success') }}
                                        </div>
                                    @endif
                                    @error('email_or_phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Send Password Reset Link') }}
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

@extends('layouts.main')

@section('title','ورود')

@section('content')
    <div class="d-flex justify-content-center pt-5 mt-5">
        <div class="col-md-8 pt-5 mt-5">
            <div class="card">
                <div class="card-header">
                    ورود
                </div>
                <div class="card-body">
                    @include('partials.alerts')
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="email" class="mb-2">ایمیل</label>
                            <input type="email" class="form-control" placeholder="ایمیل خود را وارد کنید" name="email"
                                   id="email" value="{{old('email')}}">
                        </div>

                        <div class="form-group">
                            <label for="password" class="mb-2">پسورد</label>
                            <input type="password" class="form-control" placeholder="پسورد خود را وارد کنید"
                                   name="password" id="password">
                        </div>

                        <label for="remember_me" class="inline-flex items-center mt-3 mb-3">
                            <input id="remember_me" type="checkbox" name="remember">
                            <span class="me-2">مرا به خاطر بسپار</span>
                        </label>

                        <button class="btn btn-success w-100 mb-2">
                            ورود
                        </button>

                        @if (Route::has('password.request'))
                            <a class="pt-4"
                               href="{{ route('password.request') }}">
                                رمز خود را فراموش کردید؟
                            </a>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

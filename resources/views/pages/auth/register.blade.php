@extends('layouts.main')

@section('title','ثبت نام')

@section('content')
    <div class="d-flex justify-content-center pt-5 mt-5">
        <div class="col-md-8 pt-5 mt-5">
            <div class="card">
                <div class="card-header">
                    ثبت نام
                </div>
                <div class="card-body">
                    @include('partials.alerts')
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="name" class="mb-2">نام و نام خانوادگی</label>
                            <input type="text" class="form-control" placeholder="نام و نام خانوادگی خود را وارد کنید"
                                   name="name"
                                   id="name" value="{{old('name')}}">
                        </div>

                        <div class="form-group mb-3">
                            <label for="email" class="mb-2">ایمیل</label>
                            <input type="email" class="form-control" placeholder="ایمیل خود را وارد کنید" name="email"
                                   id="email" value="{{old('email')}}">
                        </div>

                        <div class="form-group mb-3">
                            <label for="password" class="mb-2">پسورد</label>
                            <input type="password" class="form-control" placeholder="پسورد خود را وارد کنید"
                                   name="password" id="password">
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation" class="mb-2">تکرار پسورد</label>
                            <input type="password" class="form-control" placeholder="پسورد خود را دوباره وارد کنید"
                                   name="password_confirmation" id="password_confirmation">
                        </div>

                        <label for="remember_me" class="inline-flex items-center mt-3 mb-3">
                            <input id="remember_me" type="checkbox" name="remember">
                            <span class="me-2">مرا به خاطر بسپار</span>
                        </label>

                        <button class="btn btn-success w-100 mb-2">
                            ثبت نام
                        </button>

                        <a href="{{ route('login') }}">
                            قبلا ثبت نام کردید؟ وارد شوید
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


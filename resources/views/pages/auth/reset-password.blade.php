@extends('layouts.main')

@section('title','تایید بازیابی رمز عبور')

@section('content')
    <div class="d-flex justify-content-center pt-5 mt-5">
        <div class="col-md-8 pt-5 mt-5">
            <div class="card">
                <div class="card-header">
                    تایید بازیابی رمز عبور
                </div>
                <div class="card-body">
                    @include('partials.alerts')
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                        <div class="form-group mb-3">
                            <label for="email" class="mb-2">ایمیل</label>
                            <input type="email" class="form-control" placeholder="ایمیل خود را وارد کنید" name="email"
                                   id="email" value="{{old('email', request('email'))}}">
                        </div>

                        <div class="form-group mb-3">
                            <label for="password" class="mb-2">پسورد</label>
                            <input type="password" class="form-control" placeholder="پسورد خود را وارد کنید"
                                   name="password" id="password">
                        </div>

                        <div class="form-group mb-3">
                            <label for="password_confirmation" class="mb-2">تکرار پسورد</label>
                            <input type="password" class="form-control" placeholder="پسورد خود را دوباره وارد کنید"
                                   name="password_confirmation" id="password_confirmation">
                        </div>

                        <button class="btn btn-success w-100 mb-2">
                            تغییر رمز عبور
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

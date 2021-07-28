@extends('layouts.main')

@section('title','تایید موبایل')

@section('content')
    <div class="d-flex justify-content-center pt-5 mt-5">
        <div class="col-md-8 pt-5 mt-5">
            <div class="card">
                <div class="card-header">
                    تایید موبایل
                </div>
                <div class="card-body">
                    @include('partials.alerts')

                    <p>
                        پیامک تایید برای شما ارسال شد
                    </p>

                    <p>
                        در صورتی که شماره وارد شده شما در سیستم اشتباه است و یا قادر به دریافت پیامک با این شماره نیستید
                        با پشتیبانی تماس بگیرید.
                    </p>

                    <form method="post">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-8">
                                <input type="text" name="code" placeholder="کد تایید ارسال شده به موبایل"
                                       class="form-control" required>
                            </div>
                            <div class="col-4">
                                <button class="btn btn-success w-100">
                                    تایید
                                </button>
                            </div>
                        </div>
                    </form>

                    <a href="{{route('verify-mobile')}}" class="me-4">
                        دریافت دوباره پیامک تایید
                    </a>

                    <a href="{{ route('logout') }}">
                        خروج
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.main')

@section('title','تایید آدرس ایمیل')

@section('content')
    <div class="d-flex justify-content-center pt-5 mt-5">
        <div class="col-md-8 pt-5 mt-5">
            <div class="card">
                <div class="card-header">
                    تایید آدرس ایمیل
                </div>
                <div class="card-body">
                    @include('partials.alerts')

                    <p>
                        ایمیل تایید برای شما ارسال شد، لطفا پوشه spam هم بررسی کنید
                    </p>

                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf

                        <button class="btn btn-success w-100 mb-2">
                            دریافت دوباره ایمیل تایید
                        </button>

                        <a class="pt-4" href="{{ route('logout') }}">
                            خروج
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

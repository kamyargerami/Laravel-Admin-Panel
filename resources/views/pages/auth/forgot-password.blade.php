@extends('layouts.main')

@section('title','بازیابی رمز عبور')

@section('content')
    <div class="d-flex justify-content-center pt-5 mt-5">
        <div class="col-md-8 pt-5 mt-5">
            <div class="card">
                <div class="card-header">
                    بازیابی رمز عبور
                </div>
                <div class="card-body">
                    @include('partials.alerts')
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <p class="text-center">
                            جهت بازیابی رمز عبور، لطفا ایمیل خود را وارد کنید
                        </p>

                        <div class="form-group mb-3">
                            <label for="email" class="mb-2">ایمیل</label>
                            <input type="email" class="form-control" placeholder="ایمیل خود را وارد کنید" name="email"
                                   id="email" value="{{old('email')}}">
                        </div>

                        <button class="btn btn-success w-100 mb-2">
                            بازیابی رمز عبور
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

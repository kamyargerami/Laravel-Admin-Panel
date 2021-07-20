@extends('layouts.main')

@section('title','تایید پسورد')

@section('content')
    <div class="d-flex justify-content-center pt-5 mt-5">
        <div class="col-md-8 pt-5 mt-5">
            <div class="card">
                <div class="card-header">
                    تایید پسورد
                </div>
                <div class="card-body">
                    @include('partials.alerts')

                    <p>
                        لطفا پسورد خود را تایید کنید
                    </p>

                    <form method="POST" action="{{ route('password.confirm') }}">
                        @csrf

                        <div class="form-group">
                            <label for="password" class="mb-2">پسورد</label>
                            <input type="password" class="form-control" placeholder="پسورد خود را وارد کنید"
                                   name="password" id="password">
                        </div>

                        <button class="btn btn-success w-100 mb-2">
                            تایید پسورد
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


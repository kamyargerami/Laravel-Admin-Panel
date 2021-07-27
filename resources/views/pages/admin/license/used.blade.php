@extends('layouts.admin')

@section('title', 'مشتریان لایسنس ' . $license_id)

@section('content')
    <div class="card">
        <div class="card-header">
            مشتریان
            <a href="{{route('admin.license.list' , ['id' => $license_id])}}" target="_blank" class="text-dark">
                لایسنس
                {{$license_id}}
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>نام</th>
                        <th>نام خانوادگی</th>
                        <th>تلفن</th>
                        <th>ایمیل</th>
                        <th>کشور</th>
                        <th>شهر</th>
                        <th>ورژن</th>
                        <th>نام دستگاه</th>
                        <th>شماره شناسایی</th>
                        <th>شرکت</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($used_licenses as $used)
                        <tr>
                            <td>{{$used->id}}</td>
                            <td>{{$used->first_name}}</td>
                            <td>{{$used->last_name}}</td>
                            <td>{{$used->phone}}</td>
                            <td>{{$used->email}}</td>
                            <td>{{$used->country}}</td>
                            <td>{{$used->city}}</td>
                            <td>{{$used->version}}</td>
                            <td>{{$used->device_name}}</td>
                            <td>{{$used->fingerprint}}</td>
                            <td>{{$used->company}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            @include('partials.paginate', ['pages' => $used_licenses])
        </div>
    </div>
@endsection

@extends('layouts.admin')

@section('title', 'مدیریت لایسنس')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-9">
                    <p class="pt-2 mb-0">
                        لایسنس ها
                    </p>
                </div>
                <div class="col-3">
                    <button class="btn btn-sm btn-primary pull-left" data-bs-toggle="modal"
                            data-bs-target="#defaultModal"
                            data-path="{{ route('admin.license.add') }}"
                            data-title="افزودن لایسنس" data-confirm-text="افزودن">
                        <i class="fa fa-plus ps-0 ps-lg-1"></i>
                        <span class="d-none d-lg-inline-block">
                            افزودن لایسنس
                        </span>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <th>#</th>
                        <th>نوع</th>
                        <th>تعداد استفاده</th>
                        <th>حداکثر استفاده مجاز</th>
                        <th>محصول</th>
                        <th>ادمین/نماینده</th>
                        <th>کلید</th>
                        <th>ایجاد</th>
                        <th>آخرین تغییر</th>
                        <th>عملیات</th>
                    </tr>
                    @foreach($licenses as $license)
                        <tr>
                            <td>{{$license->id}}</td>
                            <td>{{__('types.license.' . $license->type)}}</td>
                            <td>{{$license->used_count}}</td>
                            <td>{{$license->max_use}}</td>
                            <td>{{__('products.' . $license->product->name)}}</td>
                            <td>{{$license->user->name}}</td>
                            <td>{{$license->key}}</td>
                            <td>{{\Morilog\Jalali\Jalalian::fromDateTime($license->created_at)}}</td>
                            <td>{{\Morilog\Jalali\Jalalian::fromDateTime($license->updated_at)}}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-secondary btn-sm dropdown-toggle"
                                            data-bs-toggle="dropdown">
                                        عملیات
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" data-bs-toggle="modal"
                                           data-bs-target="#defaultModal"
                                           data-path="{{ route('admin.license.edit', $license->id) }}"
                                           data-title="ویرایش لایسنس"
                                           data-confirm-text="ویرایش">
                                            ویرایش
                                        </a>
                                        <a class="dropdown-item" data-bs-toggle="modal"
                                           data-bs-target="#defaultModal"
                                           data-path="{{ route('admin.log', ['type' => 'License','id' => $license->id]) }}"
                                           data-title=" لاگ های لایسنس {{$license->id}}">
                                            لاگ
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
            @if($licenses->lastPage() > 1 and $licenses->lastPage() <= $licenses->currentPage())
                <div class="card-footer">
                    @include('partials.paginate', ['pages' => $licenses])
                </div>
            @endif
        </div>
@endsection

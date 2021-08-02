@extends('layouts.admin')

@section('title','کاربران')

@section('content')
    <div class="card">
        @can('read_others_data')
            <div class="card-header">
                <form>
                    <div class="row">
                        <div class="col-lg-1 mb-2">
                            <input type="number" class="form-control" placeholder="از آیدی" name="from_id"
                                   value="{{old('from_id', request('from_id'))}}" min="0" autocomplete="off">
                        </div>
                        <div class="col-lg-1 mb-2">
                            <input type="number" class="form-control" placeholder="تا آیدی" name="to_id"
                                   value="{{old('to_id', request('to_id'))}}" min="0" autocomplete="off">
                        </div>
                        <div class="col-lg-3 mb-2 mb-lg-0">
                            <input type="text" name="name" placeholder="نام" value="{{request('name')}}"
                                   class="form-control">
                        </div>
                        <div class="col-lg-2 mb-2 mb-lg-0">
                            <input type="text" name="email" placeholder="ایمیل" value="{{request('email')}}"
                                   class="form-control">
                        </div>
                        <div class="col-lg-2 mb-2">
                            <input type="text" placeholder="از ایجاد" name="from_created" autocomplete="off"
                                   class="form-control pdate" value="{{old('from_created', request('from_created'))}}">
                        </div>
                        <div class="col-lg-2 mb-2">
                            <input type="text" placeholder="تا ایجاد" name="to_created" autocomplete="off"
                                   class="form-control pdate" value="{{old('to_created', request('to_created'))}}">
                        </div>
                        <div class="col-lg-2 mb-2 mb-lg-0">
                            @include('partials.order-by',['order_by' => ['created_at','updated_at']])
                        </div>
                        <div class="col-lg-1 mb-2 mb-lg-0">
                            <input type="text" name="id" placeholder="ID" value="{{request('id')}}"
                                   class="form-control">
                        </div>
                        <div class="col-lg-2 mb-2 mb-lg-0">
                            <button class="btn btn-primary w-100">
                                جستجو
                                <i class="fa fa-search pe-2"></i>
                            </button>
                        </div>
                        <div class="col-lg-1 mb-2 mb-lg-0">
                            <a href="{{route('admin.user.list')}}" class="btn btn-warning w-100">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                        @can('admin.user.add')
                            <div class="col-lg-1 mb-2 mb-lg-0">
                                <button type="button" class="btn btn-success w-100" data-bs-toggle="modal"
                                        data-bs-target="#defaultModal"
                                        data-path="{{ route('admin.user.add') }}"
                                        data-title="افزودن کاربر" data-confirm-text="افزودن">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        @endcan
                    </div>
                </form>
            </div>
        @endcan

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <th>#</th>
                        <th>نام</th>
                        <th>ایمیل</th>
                        <th>ایمیل تایید شده</th>
                        <th>موبایل</th>
                        <th>موبایل تایید شده</th>
                        <th>عملیات</th>
                    </tr>

                    @foreach($users as $user)
                        <tr>
                            <td>{{$user->id}}</td>
                            <td>{{$user->name}}</td>
                            <td>{{$user->email}}</td>
                            <td>
                                @if($user->email_verified_at)
                                    <i class="fa fa-check text-success"></i>
                                @else
                                    <i class="fa fa-times text-danger"></i>
                                @endif
                            </td>
                            <td>{{$user->mobile}}</td>
                            <td>
                                @if($user->mobile_verified_at)
                                    <i class="fa fa-check text-success"></i>
                                @else
                                    <i class="fa fa-times text-danger"></i>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-secondary btn-sm dropdown-toggle"
                                            data-bs-toggle="dropdown">
                                        عملیات
                                    </button>
                                    <div class="dropdown-menu">
                                        @can('admin.user.edit')
                                            <a class="dropdown-item" data-bs-toggle="modal"
                                               data-bs-target="#defaultModal"
                                               data-path="{{ route('admin.user.edit', $user->id) }}"
                                               data-title="ویرایش کاربر"
                                               data-confirm-text="ویرایش">
                                                ویرایش
                                            </a>
                                        @endcan
                                        @can('admin.log')
                                            <a class="dropdown-item" data-bs-toggle="modal"
                                               data-bs-target="#defaultModal"
                                               data-path="{{ route('admin.log', ['type' => 'User','id' => $user->id]) }}"
                                               data-title="لاگ های کاربر {{$user->id}}">
                                                لاگ
                                            </a>
                                        @endcan
                                        @can('admin.user.delete')
                                            <a class="dropdown-item" href="{{route('admin.user.delete',$user->id)}}">
                                                حذف
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
        <div class="card-footer">
            @include('partials.paginate', ['pages' => $users])
        </div>
        @can('admin.notification.send')
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-lg-3">
                        <button class="btn btn-outline-success w-100" data-bs-toggle="modal"
                                data-bs-target="#notifyModal">
                            ارسال پیام به کاربران
                        </button>
                    </div>
                </div>
            </div>
        @endcan
    </div>

    @include('pages.admin.user.notify-from')
@endsection

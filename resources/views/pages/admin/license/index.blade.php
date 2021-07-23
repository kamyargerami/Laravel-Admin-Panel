@extends('layouts.admin')

@section('title', 'مدیریت لایسنس')

@section('content')
    <div class="card">
        <div class="card-header">
            <form>
                <div class="row">
                    <div class="col-lg-2">
                        <select name="product_id" class="form-select">
                            <option value="">-- محصول --</option>
                            @foreach($products as $product)
                                <option
                                    value="{{$product->id}}" {{request('product_id') == $product->id ? 'selected' : ''}}>
                                    {{__('products.' . $product->name)}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <select name="user_id" class="form-select">
                            <option value="">-- ادمین / نماینده --</option>
                            @foreach($users as $user)
                                <option {{request('user_id') == $user->id ? 'selected' : ''}}>
                                    {{$user->name}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <input name="key" type="text" class="form-control" placeholder="کلید لایسنس"
                               value="{{request('key')}}">
                    </div>
                    <div class="col-lg-2">
                        @include('partials.order-by',['order_by' => ['created_at', 'updated_at', 'max_use', 'type']])
                    </div>
                    <div class="col-lg-2">
                        <button class="btn btn-primary w-100">
                            جستجو
                        </button>
                    </div>
                    <div class="col-lg-1">
                        <a href="{{route('admin.license.list')}}" class="btn btn-warning w-100">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                    <div class="col-lg-1">
                        <button class="btn btn-success pull-left w-100" data-bs-toggle="modal"
                                data-bs-target="#defaultModal"
                                data-path="{{ route('admin.license.add') }}"
                                data-title="افزودن لایسنس" data-confirm-text="افزودن">
                            <i class="fa fa-plus"></i>
                        </button>
                    </div>
                </div>
            </form>
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
                            <td>{{$license->user->name ?? '---'}}</td>
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
        </div>
        <div class="card-footer">
            @include('partials.paginate', ['pages' => $licenses])
        </div>
    </div>
@endsection

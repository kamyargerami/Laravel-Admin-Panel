@extends('layouts.admin')

@section('title', 'مدیریت لایسنس')

@section('content')
    <div class="card">
        <div class="card-header">
            <form>
                <div class="row">
                    <div class="col-lg-2 mb-2 mb-lg-0">
                        <select name="product_id" class="form-select">
                            <option value="">-- محصول --</option>
                            @foreach($products as $product)
                                <option
                                    value="{{$product->id}}" {{request('product_id') == $product->id ? 'selected' : ''}}>
                                    {{$product->name}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2 mb-2 mb-lg-0">
                        <select name="user_id" class="form-select">
                            <option value="">-- ادمین / نماینده --</option>
                            @foreach($users as $user)
                                <option value="{{$user->id}}" {{request('user_id') == $user->id ? 'selected' : ''}} ش>
                                    {{$user->name}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2 mb-2 mb-lg-0">
                        <input name="key" type="text" class="form-control" placeholder="کلید لایسنس"
                               value="{{request('key')}}">
                    </div>
                    <div class="col-lg-2 mb-2 mb-lg-0">
                        @include('partials.order-by',['order_by' => ['created_at', 'updated_at', 'max_use', 'type']])
                    </div>
                    <div class="col-lg-2 mb-2 mb-lg-0">
                        <button type="submit" class="btn btn-primary w-100">
                            جستجو
                            <i class="fa fa-search pe-2"></i>
                        </button>
                    </div>
                    <div class="col-lg-1 mb-2 mb-lg-0">
                        <a href="{{route('admin.license.list')}}" class="btn btn-warning w-100">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                    <div class="col-lg-1 mb-2 mb-lg-0">
                        <button type="button" class="btn btn-success pull-left w-100" data-bs-toggle="modal"
                                data-bs-target="#defaultModal"
                                data-path="{{ route('admin.license.add') }}"
                                data-title="افزودن لایسنس" data-confirm-text="افزودن">
                            <i class="fa fa-plus"></i>
                        </button>
                    </div>
                </div>
            </form>
            <div class="row mt-2">
                <div class="col-lg-2 mb-2 mb-lg-0">
                    <button type="button" class="btn btn-outline-danger pull-left w-100" id="export_btn">
                        خروجی
                        <i class="fa fa-download pe-2"></i>
                    </button>
                </div>

                <div class="col-lg-2 mb-2 mb-lg-0">
                    <button type="button" class="btn btn-outline-success pull-left w-100"
                            data-bs-toggle="modal" data-bs-target="#multiUpdateModal">
                        تغییر ادمین / نماینده
                        <i class="fa fa-link pe-2"></i>
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
                        <th>وضعیت</th>
                        <th>حداکثر استفاده مجاز</th>
                        <th>استفاده شده</th>
                        <th>محصول</th>
                        <th>ادمین/نماینده</th>
                        <th>کلید</th>
                        <th>ایجاد</th>
                        <th>انقضا</th>
                        <th>عملیات</th>
                    </tr>
                    @foreach($licenses as $license)
                        <tr>
                            <td>{{$license->id}}</td>
                            <td>{{__('types.license.' . $license->type)}}</td>
                            <td>
                                @if($license->status)
                                    <p class="text-success mb-0">
                                        فعال
                                    </p>
                                @else
                                    <p class="text-danger mb-0">
                                        غیر فعال
                                    </p>
                                @endif
                            </td>
                            <td>{{$license->max_use}}</td>
                            <td>{{count(array_unique($license->used->pluck('fingerprint')->toArray()))}}</td>
                            <td>{{$license->product->name}}</td>
                            <td>{{$license->user->name}}</td>
                            <td>{{$license->key}}</td>
                            <td>{{\Morilog\Jalali\Jalalian::fromDateTime($license->created_at)}}</td>
                            <td>{{$license->expires_at ? \Morilog\Jalali\Jalalian::fromDateTime($license->expires_at)->format('Y-m-d') : '--'}}</td>
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
                                        <a class="dropdown-item" href="{{route('admin.license.delete',$license->id)}}">
                                            حذف
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

    <div class="modal fade" id="multiUpdateModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">ویرایش گروهی ادمین / نماینده</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-danger">
                        توجه: همه نتایج جستجو فعلی شامل این تغییر میشود.
                    </p>

                    <div class="form-group mt-3">
                        <select name="new_user_id" id="new_user_id" class="form-select mt-1">
                            @foreach($users as $user)
                                <option value="{{$user->id}}">{{$user->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
                    <button type="submit" class="btn btn-primary" id="multi_update_btn">تغییر گروهی</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $('#export_btn').click(function () {
            window.location = '/admin/license/export?' + $('form').serialize();
        });

        $('#multi_update_btn').click(function () {
            window.location = '/admin/license/multi-update?' + $('form').serialize() + '&new_user_id=' + $('#new_user_id').val();
        });
    </script>
@endsection

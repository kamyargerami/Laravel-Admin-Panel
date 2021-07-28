@extends('layouts.admin')

@section('title', 'مدیریت لایسنس')

@section('content')
    <div class="card">
        <div class="card-header">
            <form id="search">
                <div class="row">
                    <div class="col-lg-1 mb-2">
                        <input type="number" class="form-control" placeholder="از آیدی" name="from_id"
                               value="{{old('from_id', request('from_id'))}}" min="0" autocomplete="off">
                    </div>
                    <div class="col-lg-1 mb-2">
                        <input type="number" class="form-control" placeholder="تا آیدی" name="to_id"
                               value="{{old('to_id', request('to_id'))}}" min="0" autocomplete="off">
                    </div>
                    <div class="col-lg-2 mb-2">
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
                    <div class="col-lg-2 mb-2">
                        <select name="user_id" class="form-select">
                            <option value="">-- ادمین / نماینده --</option>
                            @foreach($users as $user)
                                <option value="{{$user->id}}" {{request('user_id') == $user->id ? 'selected' : ''}}>
                                    {{$user->name}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2 mb-2">
                        <input name="key" type="text" class="form-control" placeholder="کلید لایسنس"
                               value="{{old('key', request('key'))}}" autocomplete="off">
                    </div>
                    <div class="col-lg-2 mb-2">
                        <input type="text" placeholder="از ایجاد" name="from_created" autocomplete="off"
                               class="form-control pdate" value="{{old('from_created', request('from_created'))}}">
                    </div>
                    <div class="col-lg-2 mb-2">
                        <input type="text" placeholder="تا ایجاد" name="to_created" autocomplete="off"
                               class="form-control pdate" value="{{old('to_created', request('to_created'))}}">
                    </div>
                    <div class="col-lg-2 mb-2">
                        @include('partials.order-by',['order_by' => ['created_at', 'updated_at', 'max_use', 'type']])
                    </div>
                    <div class="col-lg-2 mb-2">
                        <input type="text" placeholder="از انقضا" name="from_expires" autocomplete="off"
                               class="form-control pdate" value="{{old('from_created', request('from_expires'))}}">
                    </div>
                    <div class="col-lg-2 mb-2">
                        <input type="text" placeholder="تا انقضا" name="to_expires" autocomplete="off"
                               class="form-control pdate" value="{{old('to_created', request('to_expires'))}}">
                    </div>
                    <div class="col-lg-2 mb-2">
                        <input type="number" name="id" placeholder="آیدی" class="form-control"
                               value="{{old('id', request('id'))}}" min="0" autocomplete="off">
                    </div>
                    <div class="col-lg-2 mb-2">
                        <input type="text" placeholder="از اولین استفاده" name="from_first_use" autocomplete="off"
                               class="form-control pdate" value="{{old('from_created', request('from_first_use'))}}">
                    </div>
                    <div class="col-lg-2 mb-2">
                        <input type="text" placeholder="تا اولین استفاده" name="to_first_use" autocomplete="off"
                               class="form-control pdate" value="{{old('to_created', request('to_first_use'))}}">
                    </div>
                    <div class="col-lg-2 mb-2">
                        <button type="submit" class="btn btn-primary w-100">
                            جستجو
                            <i class="fa fa-search pe-2"></i>
                        </button>
                    </div>
                    <div class="col-lg-1 mb-2">
                        <a href="{{route('admin.license.list')}}" class="btn btn-warning w-100">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                    <div class="col-lg-1 mb-2">
                        <button type="button" class="btn btn-success pull-left w-100" data-bs-toggle="modal"
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
                        <th>وضعیت</th>
                        <th>حداکثر استفاده مجاز</th>
                        <th>استفاده شده</th>
                        <th>محصول</th>
                        <th>ادمین/نماینده</th>
                        <th>کلید</th>
                        <th>ایجاد</th>
                        <th>اولین استفاده</th>
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
                            <td>
                                @if($used_count = count(array_unique($license->used->pluck('fingerprint')->toArray())))
                                    <a href="{{route('admin.license.used', $license->id)}}"
                                       class="text-dark no-decoration" target="_blank">
                                        {{$used_count}}
                                    </a>
                                @else
                                    0
                                @endif
                            </td>
                            <td>{{$license->product->name}}</td>
                            <td>{{$license->user->name}}</td>
                            <td>{{$license->key}}</td>
                            <td>{{\Morilog\Jalali\Jalalian::fromDateTime($license->created_at)->format('Y-m-d')}}</td>
                            <td>{{$license->expires_at ? \Morilog\Jalali\Jalalian::fromDateTime($license->expires_at)->format('Y-m-d') : '--'}}</td>
                            <td>{{$license->used->first() ? \Morilog\Jalali\Jalalian::fromDateTime($license->used->first()->created_at)->format('Y-m-d') : '--'}}</td>
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
                                           data-path="{{ route('admin.log', ['type' => 'License', 'id' => $license->id]) }}"
                                           data-title=" لاگ های لایسنس {{$license->id}}">
                                            لاگ
                                        </a>
                                        <a class="dropdown-item" href="{{route('admin.license.delete',$license->id)}}">
                                            حذف
                                        </a>
                                        @if($used_count)
                                            <a class="dropdown-item" target="_blank"
                                               href="{{route('admin.license.used', $license->id)}}">
                                                مشتریان
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
            @include('partials.paginate', ['pages' => $licenses])
        </div>
        <div class="card-body">
            <p class="text-center">
                با استفاده از دکمه های پایین میتوانید روی همه نتایج جستجو تغییرات اعمال نمایید.
                <span class="text-danger">لطفا دقت کنید</span>
            </p>
            <div class="row justify-content-center">
                <div class="col-12 col-lg-3 mb-2">
                    <button type="button" class="btn btn-outline-primary pull-left w-100" id="export_btn">
                        خروجی
                        <i class="fa fa-download pe-2"></i>
                    </button>
                </div>

                <div class="col-12 col-lg-3 mb-2">
                    <button type="button" class="btn btn-outline-success pull-left w-100" data-bs-toggle="modal"
                            data-bs-target="#multiEditModal">
                        تغییر گروهی
                        <i class="fa fa-edit pe-2"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    @include('pages.admin.license.multi-edit')
@endsection

@section('script')
    <script>
        $('#export_btn').click(function () {
            window.location = '/admin/license/export?' + $('form#search').serialize();
        });
    </script>
@endsection

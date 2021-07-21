@extends('layouts.admin')

@section('title', 'محصولات')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-9">
                    <p class="pt-2 mb-0">
                        محصولات
                    </p>
                </div>
                <div class="col-3">
                    <button class="btn btn-sm btn-primary pull-left">
                        <i class="fa fa-plus ps-0 ps-lg-1"></i>
                        <span class="d-none d-lg-inline-block">
                            افزودن محصول
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
                        <th width="50%">نام</th>
                        <th>تاریخ آخرین تغییر</th>
                        <th>عملیات</th>
                    </tr>
                    @foreach($products as $product)
                        <tr>
                            <td>{{$product->id}}</td>
                            <td>{{__('products.' . $product->name)}}</td>
                            <td>{{\Morilog\Jalali\Jalalian::fromDateTime($product->updated_at)}}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-secondary btn-sm dropdown-toggle"
                                            data-bs-toggle="dropdown">
                                        عملیات
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" data-bs-toggle="modal"
                                           data-bs-target="#defaultModal"
                                           data-path="{{ route('admin.product.edit', $product->id) }}"
                                           data-title="ویرایش محصول"
                                           data-confirm-text="ویرایش">
                                            ویرایش
                                        </a>
                                        <a class="dropdown-item" data-bs-toggle="modal"
                                           data-bs-target="#defaultModal"
                                           data-path="{{ route('admin.log', ['type' => 'Product','id' => $product->id]) }}"
                                           data-title=" لاگ های محصول {{$product->id}}">
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
        @if($products->lastPage() > 1 and $products->lastPage() <= $products->currentPage())
            <div class="card-footer">
                @include('partials.paginate', ['pages' => $products])
            </div>
        @endif
    </div>
@endsection

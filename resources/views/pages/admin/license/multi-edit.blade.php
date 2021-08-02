<div class="modal fade" id="multiEditModal">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تغییر آیتم های جستجو شده به صورت گروهی</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('admin.license.multi-update')}}" method="post">
                @csrf

                @foreach(request()->all() as $key => $value)
                    <input type="hidden" name="{{$key}}" value="{{$value}}">
                @endforeach

                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="new_type">
                            نوع
                            <i class="text-danger fa fa-warning ms-2" data-bs-toggle="tooltip"
                               data-bs-placement="right"
                               title="اگر این لایسنس قبلا استفاده شده باشد، در صورت تغییر این فیلد، می بایست مقدار تاریخ انقضا لایسنس را هم تغییر دهید، api با استفاده از این مورد انقضای یک لایسنس را تشخیص میدهد."></i>
                        </label>
                        <select name="new_type" class="form-select mt-1" id="new_type">
                            <option value="">بدون تغییر</option>
                            @foreach(\App\Models\License::Types as $type)
                                @continue($type == 'trial')
                                <option value="{{$type}}">
                                    {{__('types.license.' . $type)}}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="new_product_id">محصول</label>
                        <select name="new_product_id" class="form-select mt-1" id="new_product_id">
                            <option value="">بدون تغییر</option>
                            @foreach($products as $product)
                                <option value="{{$product->id}}">
                                    {{$product->name}}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="new_user_id">ادمین / نماینده</label>
                        <select name="new_user_id" id="new_user_id" class="form-select mt-1">
                            <option value="">بدون تغییر</option>
                            @foreach($users as $user)
                                <option value="{{$user->id}}">
                                    {{$user->name}}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="new_max_use">حداکثر استفاده</label>
                        <input type="number" name="new_max_use" id="new_max_use" class="form-control mt-1"
                               placeholder="این لایسنس چند بار قابلیت نصب دارد؟ (خالی - بدون تغییر)" min="1" max="2000">
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label for="new_status">وضعیت</label>
                                <select name="new_status" id="new_status" class="form-select mt-1">
                                    <option value="">بدون تغییر</option>
                                    <option value="1">فعال</option>
                                    <option value="0">غیر فعال</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label for="new_expires_at">
                                    تاریخ انقضا
                                    <i class="text-danger fa fa-warning ms-2" data-bs-toggle="tooltip"
                                       data-bs-placement="bottom"
                                       title="در صورتی که لایسنس استفاده شده باشد و شما مقدار آن را خالی قرار دهید این لایسنس بدون محدودیت قابل استفاده میگردد!"></i>
                                </label>
                                <input type="text" placeholder="تاریخ انقضا - (خالی - بدون تغییر)" name="new_expires_at"
                                       id="new_expires_at" autocomplete="off"
                                       class="form-control mt-1 pdate">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
                    <button type="submit" class="btn btn-primary">ویرایش گروهی</button>
                </div>
            </form>
        </div>
    </div>
</div>
